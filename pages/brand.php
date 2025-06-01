<!-- Brand Selector Include (brand.php) -->
<div class="nail-brand-root">
    <h1 class="nail-brand-title">Shop by Brand - Nail Art</h1>
    <div class="nail-brand-container">
        <?php
        $nailArtBrands = [
            "OPI", "Essie", "Sally Hansen", "China Glaze", 
            "CND", "Butter London", "Zoya", "Olive & June",
            "Deborah Lippmann", "Nails Inc", "Holo Taco", "Modelones"
        ];
        foreach ($nailArtBrands as $brand) {
            echo "<div class='nail-brand' onclick=\"nailBrandToggleActive(this, '{$brand}')\">{$brand}</div>";
        }
        ?>
    </div>
    <div id="nail-brand-description" class="nail-brand-description-box"></div>
</div>

<style>
.nail-brand-root {
    font-family: Arial, sans-serif;
    text-align: center;
    background-color:rgb(255, 255, 255);
    padding: 40px 0;
}
.nail-brand-title {
    font-size: 24px;
    margin-bottom: 40px;
}
.nail-brand-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 10px;
}
.nail-brand {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
    border: 1px solid #ddd;
    background: #fff;
}
.nail-brand.active {
    background-color: pink;
    color: white;
}
.nail-brand-description-box {
    margin-top: 30px;
    padding: 20px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    border-radius: 10px;
    background-color: #fff0f5;
    display: none;
}
</style>
<script>
// Hindari konflik global, gunakan fungsi unik
const nailBrandDescriptions = {
    "OPI": "OPI is known for its high-quality nail polishes with bold colors and long-lasting formulas.",
    "Essie": "Essie offers salon-quality nail polish with a wide range of fashionable colors.",
    "Sally Hansen": "Sally Hansen is a trusted brand offering nail care and polish at affordable prices.",
    "China Glaze": "China Glaze is popular for vibrant colors and durable nail polish finishes.",
    "CND": "CND (Creative Nail Design) is famous for its Shellac and long-wear nail systems.",
    "Butter London": "Butter London blends fashion with nail care, offering toxin-free polishes.",
    "Zoya": "Zoya offers natural and non-toxic nail polishes in a variety of shades.",
    "Olive & June": "Olive & June promotes easy at-home manicures with stylish and pastel shades.",
    "Deborah Lippmann": "Luxury nail polish brand known for clean formulas and glamorous colors.",
    "Nails Inc": "Nails Inc offers innovative nail products with high-shine finishes.",
    "Holo Taco": "Holo Taco is known for holographic and glittery nail polishes.",
    "Modelones": "Modelones is a trendy brand offering gel polish and nail kits for home use."
};

function nailBrandToggleActive(el, brandName) {
    let brands = el.closest('.nail-brand-container').querySelectorAll('.nail-brand');
    brands.forEach(function(brand) {
        brand.classList.remove('active');
    });
    el.classList.add('active');
    let descBox = document.getElementById('nail-brand-description');
    descBox.innerText = nailBrandDescriptions[brandName] || '';
    descBox.style.display = 'block';
}
</script>
