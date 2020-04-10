<div class="content">
        <div class="row">
          <div class="col-md-4">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">General Info</h5>
              </div>

              <div class="card-body">
                  <p class="description">

                    <input type="hidden" name="customer_id" id="customer_id" value="<?=$id?>">

                    <div class="row">
                      <div class="col-md-6 text-left">Login:</div>
                      <div class="col-md-6 text-left"><?=$login?></div>
                    </div>

                    <?php if($model): ?>
                      <div class="row">
                        <div class="col-md-6 text-left">STB Model:</div>
                        <div class="col-md-6 text-left"><?=$model?></div>
                      </div>
                    <?php endif; ?>

                    <?php if($serial_number): ?>
                      <div class="row">
                        <div class="col-md-6 text-left">Serial Number:</div>
                        <div class="col-md-6 text-left"><?=$serial_number?></div>
                      </div>
                    <?php endif; ?>

                    <?php if($software_version): ?>
                      <!-- <div class="row">
                        <div class="col-md-6 text-left">Software:</div>
                        <div class="col-md-6 text-left"><?=$software_version?></div>
                      </div> -->
                    <?php endif; ?>

                    <div class="row">
                      <div class="col-md-6 text-left">IP:</div>
                      <div class="col-md-6 text-left"><?=$ip?></div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 text-left">Last Active:</div>
                      <div class="col-md-6 text-left"><?=$last_active?></div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 text-left">Online Status:</div>
                      <div class="col-md-6 text-left"><?=($is_online) ? 'Online' : 'Offline'?></div>
                    </div>

                  </p>
              </div>

            </div>

          </div>
          <div class="col-md-8">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">Edit Profile</h5>
              </div>
              <div class="card-body">
                <form id="edit_account" method="POST" action="/customers/<?=$id?>">
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="<?=$name?>" name="full_name">
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" value="<?=$phone?>" name="phone">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>Account number</label>
                        <input type="text" class="form-control" value="<?=$account_number?>" name="account_number">
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Password</label>
                        <input id="password" type="text" class="form-control" value="<?=$password?>" name="password">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>Tariff Plan</label>
                        <select class="form-control" name="tariff_plan">
                          <?php foreach($tariffs as $tariff): ?>
                            <option value="<?=$tariff['external_id']?>" <?= ($tariff['external_id'] == $tariff_plan)? 'selected': ''; ?>><?=$tariff['name']?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Expiration Date</label>
                        <select class="form-control" name="end_date">
                          <option selected value="<?=$end_date?>"><?=($end_date) ? date("d M Y", strtotime($end_date)) : 'Unlimited'; ?></option>
                          <?php foreach($durations as $months): ?>
                            <option value="<?=$months?>">Extend for <?=$months?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>STB MAC</label>
                        <input type="text" class="form-control" id="stb_mac" name="stb_mac" value="<?=$stb_mac?>">
                      </div>
                    </div>

                    <div class="col-md-6 px-1">
                      <div class="form-group">
                        <label>Status</label>
                        <div>
                          <select class="form-control" name="status">
                            <option value="on" <?= ($status == true)? 'selected': '';?>>On</option>
                            <option value="off" <?= ($status == false)? 'selected': '';?>>Off</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Comment</label>
                        <textarea class="form-control textarea" name="comment"><?=$comment?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="update ml-auto mr-auto">
                      <button type="submit" class="btn btn-primary btn-round">Update Profile</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script type="text/javascript" src="/js/scripts/customer.edit.js"></script>
