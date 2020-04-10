<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header ">
        <h5 class="card-title">Add new reseller</h5>
      </div>
      <div class="card-body ">
        <form id="new_reseller" class="form-horizontal" action="/resellers" method="post">
          <div class="card-body ">
            <div class="row">
              <div class="col-md-3 pl-1">
                <div class="form-group">
                  <label class="control-label" for="name">Name</label>
                  <div>
                    <input type="text" class="form-control" id="name" name="name">
                  </div>
                </div>
              </div>

              <div class="col-md-3 pl-1">
                <div class="form-group">
                  <label class="control-label" for="login">Login</label>
                  <div>
                    <input type="text" class="form-control" id="login" name="login">
                  </div>
                </div>
              </div>

              <div class="col-md-3 pl-1">
                <div class="form-group">
                  <label class="control-label" for="password">Password</label>
                  <div>
                    <input type="text" class="form-control" id="password" name="password">
                  </div>
                </div>
              </div>

              <div class="col-md-2 pl-1">
                <div class="form-group">
                  <label class="control-label" for="balance">Balance</label>
                  <div>
                    <input type="text" class="form-control" id="balance" name="balance" placeholder="0">
                  </div>
                </div>
              </div>

              <div class="col-md-1 px-1">
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
            </div>

          </div>
          <div class="card-footer ">
            <div class="stats">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>

        </form>
      </div>


    </div>

      <div class="card ">
        <div class="card-header ">
          <h5 class="card-title">Resellers</h5>
        </div>
        <div class="card-body ">

        <div class="table-responsive">
          <table id="resellers" class="table">
            <thead>
              <tr class="headings">
                <th>Id</th> <!-- 0 -->
                <th>Name</th> <!-- 1 -->
                <th>Balance</th> <!-- 2 -->
                <th>Customers Total</th> <!-- 3 -->
                <th>Customers Active</th><!-- 4 -->
                <th>Last Active</th><!-- 5 -->
                <th>Status</th><!-- 6 -->
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/js/scripts/resellers.js"></script>
