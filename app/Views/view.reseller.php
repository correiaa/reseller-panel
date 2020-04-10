<div class="content">
        <div class="row">
          <div class="col-md-3">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">Edit profile</h5>
              </div>

              <div class="card-body">
                <form id="edit_account" method="POST" action="/resellers/<?=$id?>">
                  <div>
                    <div class="form-group">
                      <label>Login</label>
                      <input type="text" class="form-control" value="<?=$login?>" disabled>
                    </div>
                  </div>

                  <div>
                    <div class="form-group">
                      <label>New password</label>
                      <input type="password" class="form-control" name="password1" value="" id="password1">
                    </div>
                  </div>

                  <div>
                    <div class="form-group">
                      <label>Confirm new password</label>
                      <input type="password" class="form-control" name="password2" value="" id="password2">
                    </div>
                  </div>

                  <div>
                    <div class="form-group">
                      <label>Balance</label>
                      <?php if($is_me == false): ?>
                        <input type="number" class="form-control" value="<?=$balance?>" min="0" step="10" id="balance" name="balance">
                      <?php else: ?>
                        <input class="form-control" value="0" id="balance" name="balance" disabled>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div>
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" class="form-control" value="<?=$name?>" id="name" name="name">
                    </div>
                  </div>

                  <div>
                    <div class="form-group">
                      <label>Last Login</label>
                      <input type="text" class="form-control" value="<?=$last_login?>" disabled>
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
          <div class="col-md-9">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">Transactions</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="transactions" class="table">
                    <thead>
                      <tr class="headings">
                        <th>Id</th> <!-- 0 -->
                        <th>Date</th> <!-- 1 -->
                        <th>Amount</th> <!-- 2 -->
                        <th>Type</th> <!-- 3 -->
                        <th>Sender</th> <!-- 4 -->
                        <th>Recipient</th> <!-- 5 -->
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($transactions as $tr): ?>
                        <tr>
                          <td><?=$tr['id']?></td>
                          <td><?=$tr['date']?></td>
                          <td><?=$tr['amount']?></td>
                          <td><?=$tr['type']?></td>
                          <td><a href="/resellers/<?=$tr['sender_id']?>"><?=$tr['sender_name']?></a></td>
                          <td><a href="<?=$tr['recipient']['href']?>"><?=$tr['recipient_name']?></a></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script type="text/javascript" src="/js/scripts/reseller.edit.js"></script>
      <script>
        $("input[type='number']").inputSpinner()
      </script>
