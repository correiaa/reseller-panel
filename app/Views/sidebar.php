<div class="sidebar" data-color="white" data-active-color="danger">
  <div class="logo">
    <a href="/dashboard" class="simple-text logo-normal">
      Reseller Admin Panel
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="active ">
        <a href="/dashboard">
          <i class="nc-icon nc-bank"></i>
          <p>Dashboard</p>
        </a>
      </li>
      <li>
        <a href="/customers">
          <i class="nc-icon nc-bullet-list-67"></i>
          <p>Customers</p>
        </a>
      </li>
      <?php if(CurrentUser::getInstance()->isAdmin()): ?>
        <li>
          <a href="/resellers">
            <i class="nc-icon nc-single-02"></i>
            <p>Resellers</p>
          </a>
        </li>
      <?php endif; ?>
      <li>
        <a href="/transactions">
          <i class="nc-icon nc-credit-card"></i>
          <p>Transactions</p>
        </a>
      </li>

    </ul>
  </div>
</div>
