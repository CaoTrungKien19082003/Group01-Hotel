<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Profile</title>
</head>
<body class="bg-light">
    <?php 
        require('inc/header.php');
        $user_r=mysqli_fetch_assoc(mysqli_query($con, "SELECT * from `user_cred` WHERE `user_id`=1"));


    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="info_form">
                        <h5 class="mb-3 fw-bold">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" value="<?php echo $user_r['name']?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="number" name="phone" class="form-control shadow-none" value="<?php echo $user_r['phone']?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control shadow-none" value="<?php echo $user_r['dob']?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pincode</label>
                                <input type="number" name="pincode" class="form-control shadow-none" value="<?php echo $user_r['pincode']?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_r['address']?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark shadow-none custom-bg">SAVE CHANGES</button>
                    </form>
                </div>
            </div>

            <div class="col-md-4 md-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="profile">
                        <h5 class="mb-3 fw-bold">Picture</h5>
                        <img src="<?php echo 'http://127.0.0.1/Group01-Hotel/images/user/'.$user_r['pfp'] ?>" class="rounded-circle img-fluid mb-3"> <br>
                        <label class="form-label">New Picture</label>
                        <input type="file" name="pfp" accept=".jpg, .png, .jpeg, .webp" class="mb-4 form-control shadow-none">

                        <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                    </form>
                </div>
            </div>


            <div class="col-md-8 md-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="password">
                        <h5 class="mb-3 fw-bold">Change Password</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_pass" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="con_pass" class="form-control shadow-none" required>
                            </div>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                    </form>
                </div>
            </div>
           

    <?php require('inc/footer.php');?>

    <script>
        let info_form=document.getElementById('info_form');

        info_form.addEventListener('submit', function(e){
            e.preventDefault();

            let data=new FormData();

            data.append('info_form', '');
            data.append('name', info_form.elements['name'].value);
            data.append('phone', info_form.elements['phone'].value);
            data.append('dob', info_form.elements['dob'].value);
            data.append('pincode', info_form.elements['pincode'].value);
            data.append('address', info_form.elements['address'].value);

            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            if (this.responseText=='phone_already'){                
                alert('error', 'Phone already registered!');
            }       
            else if (this.responseText==0){
                alert('error', 'No changes made!');
            }
            else{
                alert('success', 'Changes Successfully Made!!');
            }

            xhr.send(data);
        });

        let profile=document.getElementById('profile');
        profile.addEventListener('submit', function(e){
            e.preventDefault();

            let data=new FormData();

            data.append('profile', '');
            data.append('pfp', profile.elements['pfp'].files[0]);

            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            if (this.responseText=='inv_file'){
                alert('error', 'The image you uploaded is invalid');
            }    
            else if (this.responseText=='inv_size'){
                alert('error', 'The image has exceeded 2MB maximum size!');
            }    
            else if (this.responseText=='upload_failed'){
                alert('error', 'The image failed to upload');
            }    
            else{
                window.location.href=window.location.pathname;
            }

            xhr.send(data);
        });

        let password=document.getElementById('password');
        password.addEventListener('submit', function(e){
            e.preventDefault();

            let data=new FormData();

            let new_pass=password.elements['new_pass'].value;
            let con_pass=password.elements['con_pass'].value;

            if (new_pass!=con_pass){
                alert('error', 'Password do not match!');
                return false;
            }

            data.append('password', '');
            data.append('new_pass', new_pass);

            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            if (this.responseText==0){                
                alert('error', 'Update failed!!');
            }       
            else{
                alert('success', 'Changes Successfully Made!!');
            }
            xhr.send(data);
        });
    </script>
</body>