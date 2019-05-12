<svg viewBox="0 0 32 32">
  <circle r="16" cx="16" cy="16" />
</svg>

<div class="pie">20%</div>
<div class="pie">60%</div>
<script type="text/javascript">
let pie = document.querySelectorAll('.pie');
pie.forEach(function(pie) {
let p = parseFloat(pie.textContent);
let NS = "http://www.w3.org/2000/svg";
let svg = document.createElementNS(NS, "svg");
let circle = document.createElementNS(NS, "circle");
let title = document.createElementNS(NS, "title");
circle.setAttribute("r", 16);
circle.setAttribute("cx", 16);
circle.setAttribute("cy", 16);
circle.setAttribute("stroke-dasharray", p + " 100");
svg.setAttribute("viewBox", "0 0 32 32");
title.textContent = pie.textContent;
pie.textContent = '';
svg.appendChild(title);
svg.appendChild(circle);
pie.appendChild(svg);
});
</script>
