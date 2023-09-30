<?php declare(strict_types=1);

use CodeIgniter\Shield\Config\Auth;

?>
<?= $this->extend(config(Auth::class)->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.users') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="d-flex justify-content-end">
        <a href="<?php echo site_url('/user-form') ?>" class="btn btn-success mb-2">Add User</a>
	</div>
    <?php
     if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
      }
     ?>
  <div class="mt-3">
     <table class="table table-bordered" id="users-list">
       <thead>
          <tr>
             <th>User Id</th>
             <th>Name</th>
             <th>Email</th>
             <th>Action</th>
          </tr>
       </thead>
       <tbody>
          <?php if($users): ?>
          <?php foreach($users as $user): ?>
          <tr>
             <td><?php echo $user->id; ?></td>
             <td><?php echo $user->name; ?></td>
             <td><?php echo $user->email; ?></td>
             <td>
              <a href="<?php echo base_url('edit-view/'.$user->id);?>" class="btn btn-primary btn-sm">Edit</a>
              <a href="<?php echo base_url('delete/'.$user->id);?>" class="btn btn-danger btn-sm">Delete</a>
              </td>
          </tr>
         <?php endforeach; ?>
         <?php endif; ?>
       </tbody>
     </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
      $('#users-list').DataTable();
  } );
</script>
<?= $this->endSection() ?>