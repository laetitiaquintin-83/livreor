    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-left">
                <p class="footer-copy">
                    <span class="footer-icon">📖</span>
                    &copy; <?php echo date("Y"); ?> Le Grimoire Enchanté — Tous droits mystiques réservés.
                </p>
                <p class="footer-quote"><?php echo $randomQuote ?? '« La magie est partout. »'; ?></p>
            </div>
            <div class="footer-right">
                <nav class="footer-nav">
                    <a href="index.php"><span>🏰</span> Sanctuaire</a>
                    <a href="livre-or.php"><span>📜</span> Grimoire</a>
                    <a href="inscription.php"><span>🔮</span> Initiation</a>
                    <a href="connexion.php"><span>🗝️</span> Portail</a>
                </nav>
                <p class="footer-made">Forgé avec ✨ par <a href="#" target="_blank" rel="noopener">Laetitia</a></p>
            </div>
        </div>
        
        <!-- Runes décoratives -->
        <div class="footer-runes">
            <span class="rune">ᚠ</span>
            <span class="rune">ᚢ</span>
            <span class="rune">ᚦ</span>
            <span class="rune">ᚨ</span>
            <span class="rune">ᚱ</span>
            <span class="rune">ᚲ</span>
            <span class="rune">ᚷ</span>
            <span class="rune">ᚹ</span>
        </div>
    </footer>

    <script>
    // Toggle simple mobile nav
    (function(){
        var btn = document.getElementById('navToggle');
        var nav = document.getElementById('mainNav');
        if (!btn || !nav) return;
        btn.addEventListener('click', function(){
            var open = nav.classList.toggle('open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    })();
    
    // Effet de survol magique sur les boutons
    document.querySelectorAll('.btn, .submit-btn').forEach(function(btn) {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    </script>

</body>
</html>
