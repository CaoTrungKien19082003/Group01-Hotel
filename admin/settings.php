<?php
    require('inc/essentials.php');
    Login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-white">
    
    <?php require('inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden" id="content">
                <h3 class="mb-4 h-font-1">Settings</h3>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-6">
                            <h5 class="card-title m-0">General Settings</h5>
                            <button type="button" class="btn btn-primary custom-bg shadow" data-bs-toggle="modal" data-bs-target="#general-settings">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>
                     
                        <h6 class="card-subtitle mb-1 fw-bold">Site title</h6>
                        <p class="card-text" id="site_title"></p>
                        <h6 class="card-subtitle mb-1 fw-bold">About us</h6>
                        <p class="card-text" id="site_about"></p>
                    </div>
                </div>

                <div class="modal fade" id="general-settings" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">General Settings</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Site Title</label>
                                        <input type="text" name="site_title" id="site_title_input" class="form-control shadow-none">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">About us</label>
                                        <textarea class="form-control shadow-none" name="site_about" id="site_about_input" rows="6"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="site_title.value=general_data.site_title, site_about.value=general_data.site_about" class="btn btn-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" onclick="update_general(site_title.value, site_about.value)" class="btn btn-primary custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    
                    </div>
                </div>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Shutdown Website</h5>
                            <div class="form-check form-switch">
                                <form>    
                                    <input onchange="update_shutdown(this.value)" class="form-check-input" type="checkbox" id="shutdown-toggle">
                                </form>    
                            </div>
                            </div>
                        <p class="card-text m-0">No visitors is allowed to book hotel room during the shutdown mode.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    
   

    <script>
        let general_data;

        function get_general(){
            let site_title=document.getElementById('site_title');
            let site_about=document.getElementById('site_about');
            
            let site_title_input=document.getElementById('site_title_input');
            let site_about_input=document.getElementById('site_about_input');
            let shutdown_toggle=document.getElementById('shutdown-toggle');

            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload=function(){
                general_data=JSON.parse(this.responseText);
                
                site_title.innerText=general_data.site_title;
                site_about.innerText=general_data.site_about;

                site_title_input.value=general_data.site_title;
                site_about_input.value=general_data.site_about;
                if (general_data.status==0){
                    shutdown_toggle.checked=false;
                    shutdown_toggle.value=0;
                }

                else{
                    shutdown_toggle.checked=true;
                    shutdown_toggle.value=1;
                }
            }
            xhr.send('get_general');
        }

        function update_general(site_title_info, site_about_info){
            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload=function(){
                var MyModal=document.getElementById('general_settings');
                var modal=bootstrap.Modal.getInstance(MyModal);
             
                console.log(this.responseText);

                if (this.responseText==1){
                    alert('success', 'Changes have been made');
                    get_general();
                }
            }
            xhr.send('site_title='+site_title_info+'&site_about='+site_about_info+'&update_general');
        }

        function update_shutdown(val){
            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload=function(){
                if (this.responseText==1 && general_data.status==0){
                    alert('success', 'Shutdown mode on!');
                }
                else{
                    alert('success', 'Shutdown mode off!');
                }

                get_general();
            }

            xhr.send('update_shutdown='+val)
        }

        window.onload=function(){
            get_general();
        }
        
    </script>

    <?php require('inc/scripts.php');?>
</body>
</html>