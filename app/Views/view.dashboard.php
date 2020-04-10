<!-- top -->


<div class="row">

  <div class="col-lg-3 col-md-12 col-sm-12">
    <div class="card card-stats">
      <div class="card-body ">
        <div class="row">
          <div class="col-5 col-md-4">
            <div class="icon-big text-center icon-info">
              <i class="nc-icon nc-money-coins text-info"></i>
            </div>
          </div>
          <div class="col-7 col-md-8">
            <div class="numbers">
              <p class="card-category">Credits Balance</p>
              <p class="card-title"><?=$balance?>
                <p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-12 col-sm-12">
    <div class="card card-stats">
      <div class="card-body ">
        <div class="row">
          <div class="col-5 col-md-4">
            <div class="icon-big text-center icon-warning">
              <i class="nc-icon nc-button-play text-success"></i>
            </div>
          </div>
          <div class="col-7 col-md-8">
            <div class="numbers">
              <p class="card-category">Active customers</p>
              <p class="card-title"><?=$active_customers?>
                <p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-12 col-sm-12">
    <div class="card card-stats">
      <div class="card-body ">
        <div class="row">
          <div class="col-5 col-md-4">
            <div class="icon-big text-center icon-warning">
              <i class="nc-icon nc-time-alarm text-danger"></i>
            </div>
          </div>
          <div class="col-7 col-md-8">
            <div class="numbers">
              <p class="card-category">Customers to expire</p>
              <p class="card-title"><?=$soon_expired_customers?>
                <p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-12 col-sm-12">
    <div class="card card-stats">
      <div class="card-body ">
        <div class="row">
          <div class="col-5 col-md-4">
            <div class="icon-big text-center icon-warning">
              <i class="nc-icon nc-user-run text-muted"></i>
            </div>
          </div>
          <div class="col-7 col-md-8">
            <div class="numbers">
              <p class="card-category">Expired Customers</p>
              <p class="card-title"><?=$expired_customers?>
                <p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- dashboard -->


      <div class="row">

        <div class="col-md-12">
          <div class="card ">
            <div class="card-header ">
              <h5 class="card-title">Add New Customer</h5>
            </div>

            <form id="new_account" class="form-horizontal" action="/customers/add" method="post">
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-6 pr-1">
                    <div class="form-group">
                      <label class="control-label" for="account_number">Account Number</label>
                      <div>
                        <input type="text" class="form-control" id="account_number" name="account_number">
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6 pl-1">
                    <div class="form-group">
                      <label class="control-label" for="full_name">Full Name</label>
                      <div>
                        <input type="text" class="form-control require-group" id="full_name" name="full_name">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 pr-1">
                    <div class="form-group">
                      <label class="control-label" for="login">Login</label>
                      <div>
                        <input type="text" class="form-control" id="login" name="login">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 px-1">
                    <div class="form-group">
                      <label class="control-label" for="password">Password</label>
                      <div>
                        <input type="text" class="form-control" id="password" name="password">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 pl-1">
                    <div class="form-group">
                      <label class="control-label" for="stb_mac">STB MAC</label>
                      <div>
                        <input type="text" class="form-control require-group" id="stb_mac" name="stb_mac">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3 pr-1">
                    <div class="form-group">
                      <label class="control-label" for="tariff_plan">Tariff Plan</label>
                      <div>
                        <select class="form-control" id="tariff_plan" name="tariff_plan">
                          <?php foreach($tariffs as $t): ?>
                            <option value="<?=$t['external_id']?>" <?= ($t['user_default']) ? 'selected' : '' ?>><?=$t['name']?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3 px-1">
                    <div class="form-group">
                      <label>Status</label>
                      <div>
                        <select class="form-control" name="status">
                          <option value="on" 'selected'>On</option>
                          <option value="off">Off</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3 px-1">
                    <div class="form-group">
                      <label class="control-label" for="subscription">Subscription</label>
                      <div>
                        <select class="form-control" id="subscription" name="subscription">
                          <?php  foreach($durations as $months): ?>
                            <option value="<?=$months?>"><?= ($free_trial_period == $months) ? "$months free trial" : $months?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3 pl-1">
                    <div class="form-group">
                      <label class="control-label" for="phone">Phone</label>
                      <div>
                        <input type="text" class="form-control" id="phone" name="phone">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label" for="comment">Comment</label>
                      <div>
                        <textarea type="text" class="form-control" id="comment" name="comment"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>


          </div>
        </div>

      </div>

<script type="text/javascript" src="/js/scripts/customers.dashboard.js"></script>
<script type="text/javascript" src="/js/scripts/transactions.dashboard.js"></script>
