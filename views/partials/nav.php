<!-- NAV BAR -->
  <div class="container mx-auto">
      <div class="flex">
          <a class="text-center no-underline <?php echo ($uri == 'aaa/events' ? 'bg-grey-darkest text-grey' : 'bg-red-light hover:bg-red text-red-darkest'); ?> font-bold p-4 rounded-tl flex-1" href="events.php">Events</a>
          <a class="text-center no-underline <?php echo ($uri == 'aaa/updates' ? 'bg-grey-darkest text-grey' : 'bg-blue-light hover:bg-blue text-blue-darkest'); ?> font-bold p-4 flex-1" href="updates.php">Updates</a>
          <a class="text-center no-underline <?php echo ($uri == 'aaa/codes' ? 'bg-grey-darkest text-grey' : 'bg-green-light hover:bg-green text-green-darkest'); ?> font-bold p-4 flex-1" href="codes.php">Codes</a>
          <a class="text-center no-underline <?php echo ($uri == 'aaa/billings' ? 'bg-grey-darkest text-grey' : 'bg-orange-light hover:bg-orange text-orange-darkest'); ?> font-bold p-4 flex-1" href="billings.php">Billings</a>
        </div>
  </div>
  <!-- END NAV BAR -->