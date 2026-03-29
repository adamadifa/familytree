<div class="relative w-full h-[65vh] bg-white overflow-hidden">
    <div id="tree-container" class="w-full h-full cursor-grab active:cursor-grabbing" wire:ignore>
        <!-- D3.js will render here -->
    </div>

    <!-- Zoom Controls -->
    <div class="absolute bottom-4 right-4 flex gap-2">
        <button onclick="zoomIn()" class="p-2.5 bg-white text-slate-600 rounded-lg border border-slate-200 hover:border-indigo-400 hover:text-indigo-500 transition shadow-sm" title="Perbesar">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </button>
        <button onclick="zoomOut()" class="p-2.5 bg-white text-slate-600 rounded-lg border border-slate-200 hover:border-indigo-400 hover:text-indigo-500 transition shadow-sm" title="Perkecil">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
        </button>
        <button onclick="resetZoom()" class="p-2.5 bg-white text-slate-600 rounded-lg border border-slate-200 hover:border-indigo-400 hover:text-indigo-500 transition shadow-sm" title="Reset">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4V4z"/></svg>
        </button>
    </div>

    @assets
    <script src="https://d3js.org/d3.v7.min.js"></script>
    @endassets

    @script
    <script>
        $wire.on('treeDataUpdated', (event) => {
            const data = (event && event.length > 0) ? event[0] : event;
            renderTree(data);
        });

        function renderTree(newData = null) {
            const containerEl = document.getElementById('tree-container');
            if(!containerEl) return;

            // Ensure we have dimensions before drawing
            if (containerEl.clientWidth === 0) {
                setTimeout(() => renderTree(newData), 100);
                return;
            }

            const members = (newData && newData.members) ? newData.members : @json($members);
            const relationships = (newData && newData.relationships) ? newData.relationships : @json($relationships);

            const width = containerEl.clientWidth;
            const height = containerEl.clientHeight;
            // ... (rest of the D3 logic)

            const parentChildRels = relationships.filter(r => r.relationship_type === 'parent_child');
            const spouseRels = relationships.filter(r => r.relationship_type === 'spouse');

            const memberMap = {};
            members.forEach(m => memberMap[m.id] = m);

            const childToParents = {}; 
            parentChildRels.forEach(r => {
                if (!childToParents[r.person_b_id]) childToParents[r.person_b_id] = [];
                childToParents[r.person_b_id].push(r.person_a_id);
            });

            const personSpouses = {}; 
            const spouseRelMap = {};
            spouseRels.forEach(r => {
                if (!personSpouses[r.person_a_id]) personSpouses[r.person_a_id] = [];
                if (!personSpouses[r.person_b_id]) personSpouses[r.person_b_id] = [];
                personSpouses[r.person_a_id].push(r.person_b_id);
                personSpouses[r.person_b_id].push(r.person_a_id);
                
                spouseRelMap[r.person_a_id + '_' + r.person_b_id] = r;
                spouseRelMap[r.person_b_id + '_' + r.person_a_id] = r;
            });

            const allChildIds = new Set(Object.keys(childToParents).map(Number));
            const candidateRoots = members.filter(m => {
                if (allChildIds.has(m.id)) return false;
                const spouses = personSpouses[m.id] || [];
                const isMarriedToChild = spouses.some(spouseId => allChildIds.has(spouseId));
                if (isMarriedToChild) return false;
                return true;
            });
            
            function getChildCount(id) {
                return (Object.keys(childToParents).filter(cid => childToParents[cid].includes(id))).length;
            }

            candidateRoots.sort((a, b) => {
                const aChildren = getChildCount(a.id);
                const bChildren = getChildCount(b.id);
                if (aChildren !== bChildren) {
                    return bChildren - aChildren;
                }
                return new Date(a.birth_date || '2099') - new Date(b.birth_date || '2099');
            });
            
            const primaryRoot = candidateRoots.length > 0 ? candidateRoots[0] : members[0];
            const assignedChildren = new Set();
            
            function getChildrenForPartnership(parent1, parent2) {
                const p1Children = Object.keys(childToParents).map(Number).filter(cid => childToParents[cid].includes(parent1));
                const spousesOfP1 = personSpouses[parent1] || [];

                if (parent2 === null) {
                    return p1Children.filter(cid => {
                        const parents = childToParents[cid];
                        const hasKnownSpouseParent = parents.some(p => p != parent1 && spousesOfP1.includes(p));
                        if (hasKnownSpouseParent) return false;
                        if (parents.length === 1 && spousesOfP1.length === 1) return false;
                        return true;
                    });
                } else {
                    const baseChildren = p1Children.filter(cid => childToParents[cid].includes(parent2));
                    if (spousesOfP1.length === 1 && spousesOfP1[0] === parent2) {
                        const singleParentChildren = p1Children.filter(cid => childToParents[cid].length === 1);
                        return [...new Set([...baseChildren, ...singleParentChildren])];
                    }
                    return baseChildren;
                }
            }

            function buildFamilyTree(bloodId) {
                const spouses = personSpouses[bloodId] || [];
                if (spouses.length === 0) {
                    const myChildren = getChildrenForPartnership(bloodId, null).filter(cid => !assignedChildren.has(cid));
                    myChildren.forEach(cid => assignedChildren.add(cid));
                    const childrenNodes = myChildren.flatMap(cid => buildFamilyTree(cid));
                    return [{
                        id: `node_${bloodId}_single`,
                        primary: memberMap[bloodId], 
                        spouse: null, 
                        _virtual: false,
                        children: childrenNodes.length > 0 ? childrenNodes : undefined
                    }];
                }
                const nodes = spouses.map(spouseId => {
                    const myChildren = getChildrenForPartnership(bloodId, spouseId).filter(cid => !assignedChildren.has(cid));
                    myChildren.forEach(cid => assignedChildren.add(cid));
                    const childrenNodes = myChildren.flatMap(cid => buildFamilyTree(cid));
                    const rel = spouseRelMap[bloodId + '_' + spouseId];
                    let isDivorced = false;
                    if (rel && rel.metadata) {
                        try {
                            const meta = typeof rel.metadata === 'string' ? JSON.parse(rel.metadata) : rel.metadata;
                            if (meta.status === 'divorced') isDivorced = true;
                        } catch(e) {}
                    }
                    return {
                        id: `node_${bloodId}_${spouseId}`,
                        primary: memberMap[bloodId], 
                        spouse: memberMap[spouseId], 
                        isDivorced: isDivorced,
                        _virtual: false,
                        children: childrenNodes.length > 0 ? childrenNodes : undefined
                    };
                });
                const unknownSpouseChildren = getChildrenForPartnership(bloodId, null).filter(cid => !assignedChildren.has(cid));
                if (unknownSpouseChildren.length > 0) {
                    unknownSpouseChildren.forEach(cid => assignedChildren.add(cid));
                    const childrenNodes = unknownSpouseChildren.flatMap(cid => buildFamilyTree(cid));
                    nodes.push({
                        id: `node_${bloodId}_unknown_spouse`,
                        primary: memberMap[bloodId], 
                        spouse: null, 
                        _virtual: false,
                        children: childrenNodes.length > 0 ? childrenNodes : undefined
                    });
                }
                return nodes;
            }

            let treeData;
            if (primaryRoot) {
                const mainNodes = buildFamilyTree(primaryRoot.id);
                if (mainNodes.length === 1) {
                    treeData = mainNodes[0];
                } else if (mainNodes.length > 1) {
                    treeData = { id: 'vroot', _virtual: true, children: mainNodes };
                } else {
                    treeData = { id: 'empty', _virtual: true, children: undefined };
                }
            } else {
                treeData = { id: 'empty', _virtual: true, children: undefined };
            }

            const cardW = 110;
            const cardH = 130;
            const coupleGap = cardW + 80;
            const root = d3.hierarchy(treeData);

            d3.tree()
                .nodeSize([cardW + 80, cardH + 100])
                .separation((a, b) => {
                    let wA = a.data.spouse ? 1.5 : 1;
                    let wB = b.data.spouse ? 1.5 : 1;
                    return (wA + wB) / 2 + (a.parent === b.parent ? 0.2 : 0.5);
                })(root);

            const zoom = d3.zoom().scaleExtent([0.2, 3]).on("zoom", e => g.attr("transform", e.transform));
            
            // Simpan posisi terakhir jika sedang update data
            let transformToApply = d3.zoomIdentity.translate(width / 2, 80);
            const oldSvg = d3.select("#tree-container svg");
            if (!oldSvg.empty()) {
                const currentTransform = d3.zoomTransform(oldSvg.node());
                if (currentTransform && newData) { 
                    transformToApply = currentTransform;
                }
            }

            containerEl.innerHTML = ''; 
            const svg = d3.select("#tree-container").append("svg").attr("width", width).attr("height", height).call(zoom);
            const g = svg.append("g");
            
            svg.call(zoom.transform, transformToApply);

            window.zoomIn = () => svg.transition().duration(300).call(zoom.scaleBy, 1.3);
            window.zoomOut = () => svg.transition().duration(300).call(zoom.scaleBy, 0.7);
            window.resetZoom = () => svg.transition().duration(300).call(zoom.transform, initTransform);

            const primaryColor = "#6366f1";
            const spouseColor = "#ec4899";
            const linkG = g.append("g");

            root.links().forEach(link => {
                if (link.source.data._virtual) return;
                const sx = link.source.x;
                const sy = link.source.y + (cardH / 2) + 20;
                let tx = link.target.x;
                if (link.target.data.spouse) tx += -coupleGap / 2;
                const ty = link.target.y - (cardH / 2);
                const midY = sy + (ty - sy) / 2;

                linkG.append("path")
                    .attr("d", `M${sx},${sy} L${sx},${midY} L${tx},${midY} L${tx},${ty}`)
                    .attr("stroke", primaryColor).attr("stroke-width", 1.5).attr("fill", "none").attr("opacity", 0.6);

                linkG.append("path")
                    .attr("d", `M${tx-3},${ty-4} L${tx},${ty} L${tx+3},${ty-4}`)
                    .attr("stroke", primaryColor).attr("stroke-width", 1.5).attr("fill", "none").attr("opacity", 0.6);
            });

            root.descendants().forEach(d => {
                if (d.data._virtual) return;
                const hasChildren = d.children && d.children.length > 0;
                if (d.data.spouse) {
                    const heartIcon = d.data.isDivorced ? "💔" : "❤";
                    const heartColor = d.data.isDivorced ? "#ef4444" : spouseColor;
                    linkG.append("line").attr("x1", d.x - coupleGap/2 + cardW/2).attr("y1", d.y).attr("x2", d.x + coupleGap/2 - cardW/2).attr("y2", d.y)
                        .attr("stroke", heartColor).attr("stroke-width", 1.5).attr("opacity", 0.6);
                    linkG.append("circle").attr("cx", d.x).attr("cy", d.y).attr("r", 12).attr("fill", "white").attr("stroke", heartColor).attr("stroke-width", 1).attr("opacity", 0.8);
                    linkG.append("text").attr("x", d.x).attr("y", d.y + 4).attr("text-anchor", "middle").attr("font-size", d.data.isDivorced ? "12px" : "14px").attr("fill", heartColor).text(heartIcon);
                    if (hasChildren) {
                        linkG.append("line").attr("x1", d.x).attr("y1", d.y + 12).attr("x2", d.x).attr("y2", d.y + (cardH / 2) + 20)
                            .attr("stroke", primaryColor).attr("stroke-width", 1.5).attr("opacity", 0.6);
                    }
                } else if (hasChildren) {
                    linkG.append("line").attr("x1", d.x).attr("y1", d.y + cardH/2).attr("x2", d.x).attr("y2", d.y + (cardH / 2) + 20)
                        .attr("stroke", primaryColor).attr("stroke-width", 1.5).attr("opacity", 0.6);
                }
            });

            const nodeG = g.append("g");
            function drawCard(parentG, memberData, xOffset, yOffset, isSpouse = false, isDivorced = false) {
                const cardGroup = parentG.append("g").attr("transform", `translate(${xOffset}, ${yOffset})`);
                cardGroup.append("rect").attr("width", cardW + 40).attr("height", cardH + 40).attr("x", -(cardW / 2) - 20).attr("y", -(cardH / 2) - 20).attr("fill", "transparent").style("pointer-events", "all");
                cardGroup.on("mouseenter", function() {
                    d3.select(this).select(".add-btn").transition().duration(200).style("opacity", 1).style("pointer-events", "all");
                }).on("mouseleave", function() {
                    d3.select(this).select(".add-btn").transition().duration(200).style("opacity", 0).style("pointer-events", "none");
                });

                const card = cardGroup.append("g").style("cursor", "pointer").on("click", (e) => {
                    e.stopPropagation();
                    Livewire.dispatch('memberSelected', { id: memberData.id });
                });

                card.append("rect").attr("width", cardW).attr("height", cardH).attr("x", -cardW / 2).attr("y", -cardH / 2).attr("rx", 6)
                    .attr("fill", isSpouse ? (isDivorced ? "#fef2f2" : "#fdf2f8") : "white").attr("stroke", isSpouse ? (isDivorced ? "#ef4444" : spouseColor) : primaryColor)
                    .attr("stroke-width", 1.5).style("filter", "drop-shadow(0 2px 4px rgba(0,0,0,0.05))");

                card.append("circle").attr("cx", 0).attr("cy", -25).attr("r", 28).attr("fill", isSpouse ? (isDivorced ? "#fee2e2" : "#fce7f3") : "#e0e7ff");

                if (memberData.photo_path) {
                    const clipId = `clip-${memberData.id}-${Math.floor(Math.random() * 1000000)}`;
                    card.append("defs").append("clipPath").attr("id", clipId).append("circle").attr("cx", 0).attr("cy", -25).attr("r", 28);
                    card.append("image").attr("href", '/storage/' + memberData.photo_path).attr("x", -28).attr("y", -53).attr("width", 56).attr("height", 56).attr("preserveAspectRatio", "xMidYMid slice").attr("clip-path", `url(#${clipId})`);
                } else {
                    card.append("text").attr("text-anchor", "middle").attr("y", -15).style("font-size", "26px").text(memberData.gender === 'M' ? '👨🏻‍💼' : '👩🏻‍💼');
                }

                card.append("text").attr("text-anchor", "middle").attr("y", 25).attr("fill", "#1e293b").style("font-size", "14px").style("font-weight", "700").style("font-family", "Inter, sans-serif").text(memberData.first_name);

                const birthYear = memberData.birth_date ? memberData.birth_date.substring(0, 4) : '';
                if (birthYear) {
                    card.append("rect").attr("x", -30).attr("y", 35).attr("width", 28).attr("height", 14).attr("rx", 7).attr("fill", isSpouse ? (isDivorced ? "#ef4444" : spouseColor) : primaryColor);
                    card.append("text").attr("text-anchor", "middle").attr("x", -16).attr("y", 45).attr("fill", "white").style("font-size", "9px").style("font-weight", "700").style("font-family", "Inter, sans-serif").text(birthYear);
                    card.append("text").attr("x", 2).attr("y", 45).attr("fill", "#94a3b8").style("font-size", "9px").style("font-family", "Inter, sans-serif").text("Tahun");
                }

                if (memberData.can_manage) {
                    const buttons = cardGroup.append("g").attr("class", "add-btn").style("opacity", 0).style("pointer-events", "none");
                    function drawBtn(cx, cy, relType, iconColor, tooltip) {
                        const btn = buttons.append("g").attr("transform", `translate(${cx}, ${cy})`).style("cursor", "pointer").on("click", (e) => {
                            e.stopPropagation();
                            Livewire.dispatch('openAddMemberModal', { relativeId: memberData.id, relationshipType: relType });
                        });
                        btn.append("circle").attr("r", 12).attr("fill", "white").attr("stroke", iconColor).attr("stroke-width", 1.5).style("filter", "drop-shadow(0 2px 3px rgba(0,0,0,0.15))");
                        btn.append("text").attr("text-anchor", "middle").attr("y", 4).attr("fill", iconColor).style("font-size", "14px").style("font-weight", "bold").text("+");
                        btn.append("title").text(tooltip);
                    }
                    drawBtn(0, -cardH/2, 'parent', primaryColor, 'Tambah Orang Tua');
                    drawBtn(0, cardH/2, 'child', '#10b981', 'Tambah Anak');
                    drawBtn(cardW/2, 0, 'spouse', spouseColor, 'Tambah Pasangan');
                    drawBtn(-cardW/2, 0, 'sibling', '#f59e0b', 'Tambah Saudara');
                }
            }

            const nodes = nodeG.selectAll("g.node").data(root.descendants().filter(d => !d.data._virtual)).join("g").attr("class", "node").attr("transform", d => `translate(${d.x}, ${d.y})`);
            nodes.each(function(d) {
                const group = d3.select(this);
                if (d.data.spouse) {
                    drawCard(group, d.data.primary, -coupleGap / 2, 0, false, false);
                    drawCard(group, d.data.spouse, coupleGap / 2, 0, true, d.data.isDivorced);
                } else {
                    drawCard(group, d.data.primary, 0, 0, false, false);
                }
            });
        }

        renderTree();
    </script>
    @endscript
</div>
