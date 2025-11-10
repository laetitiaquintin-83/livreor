    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-left">
                <p class="footer-copy">&copy; <?php echo date("Y"); ?> Livre d'Or — Tous droits réservés.</p>
                <p class="footer-made">Développé par <a href="https://monsite.com" target="_blank" rel="noopener">Laetitia</a></p>
            </div>
            <div class="footer-right">
                <nav class="footer-nav">
                    <a href="index.php">Accueil</a>
                    <a href="livre-or.php">Livre d'or</a>
                    <a href="inscription.php">Inscription</a>
                    <a href="connexion.php">Connexion</a>
                </nav>
            </div>
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
    </script>

</body>
</html>
