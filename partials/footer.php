<?php
// MotoCare Digital - Partial: Footer
?>

  <!-- ======================================================== -->
  <!-- FOOTER -->
  <!-- ======================================================== -->
  <footer class="w-full bg-surface-container-low shadow-[0_-1px_8px_rgba(0,0,0,0.02)] py-space-lg border-t border-surface-container-high">
    <div class="max-w-[1320px] mx-auto px-margin md:px-margin-md lg:px-margin-lg flex flex-col md:flex-row items-center justify-between gap-space-md text-on-surface-variant font-body-sm text-body-sm">
      <div class="flex items-center gap-2">
        <span class="font-bold text-on-surface">MotoCare Digital</span>
        <span>•</span>
        <span>Sistem Telemetri &amp; Buku Servis Digital Berkala</span>
      </div>
      <div class="flex items-center gap-space-lg">
        <span class="flex items-center gap-space-xs">
          <span class="inline-block w-2 h-2 rounded-full bg-secondary"></span>
          <span>Sistem Servis Normal (WIB)</span>
        </span>
        <button onclick="App.openUserSwitcher()" class="hover:text-primary transition-colors underline cursor-pointer">
          Ganti Akun Demo
        </button>
      </div>
    </div>
  </footer>

  <!-- Application Main Script -->
  <script src="assets/js/app.js"></script>
</body>
</html>
