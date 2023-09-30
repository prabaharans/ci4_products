<?php
// echo '<pre>';
// print_r($user_obj->identities[0]);
// echo '</pre>';
// die;
?>
<!DOCTYPE html>
<html>
<head>
  <title>Codeigniter 4 CRUD - Edit User Demo</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    .container {
      max-width: 500px;
    }
    .error {
      display: block;
      padding-top: 5px;
      font-size: 14px;
      color: red;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <form method="post" id="update_user" name="update_user"
    action="<?= site_url('/update') ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $user_obj->id; ?>">
      <input type="hidden" name="identities_id" id="identities_id" value="<?php echo $user_obj->identities[0]->id; ?>">
      <div class="form-group">
        <input type="text" name="name" class="form-control" inputmode="text" autocomplete="name" placeholder="<?= lang('Auth.name') ?>" value="<?php echo $user_obj->identities[0]->name; ?>" required />
      </div>
      <!-- Username -->
      <div class="form-group">
          <input type="text" class="form-control" name="username" inputmode="text" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?php echo $user_obj->username; ?>" required />
      </div>
      <!-- Email -->
      <div class="form-group">
          <input type="email" class="form-control" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?php echo $user_obj->email; ?>" required />
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-danger btn-block">Save Data</button>
      </div>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
  <script>
    if ($("#update_user").length > 0) {
      $("#update_user").validate({
        rules: {
          name: {
            required: true,
          },
          email: {
            required: true,
            maxlength: 60,
            email: true,
          },
        },
        messages: {
          name: {
            required: "Name is required.",
          },
          email: {
            required: "Email is required.",
            email: "It does not seem to be a valid email.",
            maxlength: "The email should be or equal to 60 chars.",
          },
        },
      })
    }
  </script>
</body>
</html>