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
                                    <input onchange="update_shutdown(this.value)" class="form-check-input" type="checkbox" id="shutdown_toggle">
                                </form>    
                            </div>
                            </div>
                        <p class="card-text m-0">No visitors is allowed to book hotel room during the shutdown mode.</p>
                    </div>
                </div>

                <div class="card border-0 shadow mb-5">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-6">
                            <h5 class="card-title m-0">Contact Settings</h5>
                            <button type="button" class="btn btn-primary custom-bg shadow" data-bs-toggle="modal" data-bs-target="#contacts_settings">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>

                        <div class="row justify-content-between mt-2">
                            <div class="col-lg-6">
                                <div class="mb-4">                            
                                    <h6 class="card-subtitle mb-1 fw-bold">Address</h6>
                                    <p class="card-text" id="address"></p>
                                </div>
                                <div class="mb-4">                            
                                    <h6 class="card-subtitle mb-1 fw-bold">Google Map</h6>
                                    <p class="card-text" id="gmap"></p>
                                </div>
                                <div class="mb-4">                            
                                    <h6 class="card-subtitle mb-1 fw-bold">Phone numbers</h6>
                                    <p class="card-text">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="phone_01"></span>
                                    </p>
                                    <p class="card-text">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span id="phone_02"></span>
                                    </p>
                                </div>
                                <div class="mb-4">                            
                                    <h6 class="card-subtitle mb-1 fw-bold">E-mail</h6>
                                    <p class="card-text" id="email"></p>
                                </div>

                                
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-4">                            
                                    <h6 class="card-subtitle mb-1 fw-bold">Social Links</h6>
                                </div>

                                <div class="mb-4">                            
                                    <p class="card-text mb-1" id="facebook">
                                        <i class="bi bi-facebook"></i>
                                        <span id="fb"></span>
                                    </p>
                                    <p class="card-text mb-1" id="twitter">
                                        <i class="bi bi-twitter"></i>
                                        <span id="tw"></span>
                                    </p>
                                    <p class="card-text mb-1" id="instagram">
                                        <i class="bi bi-instagram"></i>
                                        <span id="insta"></span>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <h6 class="card-subtitle mb-1 fw-bold">iFrame</h6>
                                    <iframe id="iframe" loading="lazy" class="border p-2 w-100"></iframe>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal fade"  id="contacts_settings" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form>
                            <div class="modal-content justify-content-center" style="width: 700px; position: fixed">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Contacts Settings</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="contain-fluid p-0">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-1 text-center">
                                                    <label class="form-label h-font-1 fw-bold">Address</label>
                                                    <input type="text" name="address" id="address_input" class="form-control shadow-none">
                                                </div>

                                                <div class="mb-1 text-center">
                                                    <label class="form-label h-font-1 fw-bold">Google Map Link</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="bi bi-google"></i></span>
                                                        <input type="text" name="gmap" id="gmap_input" class="form-control shadow-none">
                                                    </div>
                                                </div>

                                                <div class="mb-3 text-center">
                                                    <label class="form-label h-font-1 fw-bold mb-1">Phone Numbers</label>
                                                    <div class="input-group mb-1">
                                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="phone_01" id="phone_01_input" class="form-control shadow-none">
                                                    </div>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                        <input type="text" name="phone_02" id="phone_02_input" class="form-control shadow-none">
                                                    </div>
                                                </div>

                                                <div class="mb-3 text-center">
                                                    <label class="form-label h-font-1 fw-bold mb-1">E-mail</label>
                                                    <div class="input-group mb-1">
                                                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                                                        <input type="text" name="email" id="email_input" class="form-control shadow-none">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3 text-center">
                                                    <label class="form-label h-font-1 fw-bold mb-1">Social Sites</label>
                                                    <div class="input-group mb-1">
                                                        <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                                        <input type="text" name="fb" id="fb_input" class="form-control shadow-none">
                                                    </div>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                                                        <input type="text" name="tw" id="tw_input" class="form-control shadow-none">
                                                    </div>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                                        <input type="text" name="insta" id="insta_input" class="form-control shadow-none">
                                                    </div>
                                                </div>

                                                <div class="mb-1 text-center">
                                                    <label class="form-label h-font-1 fw-bold">iFrame Source</label>
                                                    <input type="text" name="iframe" id="iframe_input" class="form-control shadow-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="contacts_inp(contacts_data)" class="btn btn-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    
                    </div>
                </div>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3 justify-content-between">
                            <h5 class="card-title m-0">Management Team Settings</h5>
                            <button type="button" class="btn btn-primary custom-bg shadow" data-bs-toggle="modal" data-bs-target="#team_settings">
                            <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>

                        <div class="row" id="team_data">
                        <div class="col-md-2 mb-3">
                    <div class="card-deck d-flex">
                        <div class="card text-center" style="width: 18rem;">
                            <img src="http://127.0.0.1/Group01-Hotel/images/team/$row[picture]">
                            <div class="card-img-overlay text-end">
                                <button class="btn btn-danger btn-sm shadow-none h-font-1" onclick="remove_member($row[number])">
                                    <i class="bi bi-trash"></i>Delete
                                </button>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center h-font-1 text-info">$row[name]</h5>
                            </div>
                        </div>        
                    </div>
                </div>
                        </div>
                    </div>
            </div>

                <div class="modal fade" id="team_settings" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Management Team Settings</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="member_name" id="member_name_input" class="form-control shadow-none">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Picture</label>
                                        <input type="file" name="member_pic" id="member_pic_input" accept="[.jpg, .png, .webp, .jpeg]" class="form-control shadow-none">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="" class="btn btn-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" onclick="" class="btn btn-primary custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
    
    

    <script>
        let general_data, contacts_data;

        let contacts_settings=document.getElementById('contacts_settings');
        let team_settings=document.getElementById('team_settings');
        let member_name_input=document.getElementById('member_name_input');
        let member_pic_input=document.getElementById('member_pic_input');

        function get_general(){
            let site_title=document.getElementById('site_title');
            let site_about=document.getElementById('site_about');

            let site_title_input=document.getElementById('site_title_input');
            let site_about_input=document.getElementById('site_about_input');

            let shutdown_toggle=document.getElementById('shutdown_toggle');

            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload=function(){
                general_data=JSON.parse(this.responseText);
                site_title.innerText=general_data.site_title;
                site_about.innerText=general_data.site_about;

                site_title_input.value=general_data.site_title;
                site_about_input.value=general_data.site_about;

                if (general_data.shutdown==0){
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
                
                if (this.responseText==1 && general_data.shutdown==0){
                    alert('success', 'Shutdown Mode On!');
                    
                }
                else{
                    alert('success', 'Shutdown Mode Off!');   
                }
                get_general();
            }
            xhr.send('update_shutdown='+val);
        }


        function get_contacts(){
            let contacts_p_id=['address', 'gmap', 'phone_01', 'phone_02', 'email', 'fb', 'tw', 'insta'];            
            let iframe=document.getElementById('iframe');

            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload=function(){
                contacts_data=JSON.parse(this.responseText);
                contacts_data=Object.values(contacts_data);

                for (i=0; i<contacts_p_id.length; i++){
                    document.getElementById(contacts_p_id[i]).innerText=contacts_data[i+1];
                }

                iframe.src=contacts_data[9];
                contacts_inp(contacts_data);
            }
            xhr.send('get_contacts');            
        }

        function contacts_inp(data){
            let contacts_inp_id=['address_input', 'gmap_input', 'phone_01_input', 'phone_02_input', 'email_input', 'fb_input', 'tw_input', 'insta_input', 'iframe_input'];
            for (i=0; i<contacts_inp_id.length; i++){
                document.getElementById(contacts_inp_id[i]).value=data[i+1];
            }
        }

        contacts_settings.addEventListener('submit', function(e){
            e.preventDefault();
            update_contacts();
        });

        function update_contacts(){
            let index=['address', 'gmap', 'phone_01', 'phone_02', 'email', 'fb', 'tw', 'insta', 'iframe'];
            let contact_input_id=['address_input', 'gmap_input', 'phone_01_input', 'phone_02_input', 'email_input', 'fb_input', 'tw_input', 'insta_input', 'iframe_input'];
            let data_str="";
            for (i=0; i<index.length; i++){
                data_str+=index[i]+"="+document.getElementById(contact_input_id[i]).value+'&';
            }

            data_str+="update_contacts";
            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload=function(){
                if (this.responseText==1){
                    alert('success', 'Changes have been made!');
                    get_contacts();
                }
            }
           
            xhr.send(data_str);
        };

        

        team_settings.addEventListener('submit', function(e){
            e.preventDefault();
            add_member();
        });
        
        function get_members(){
            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload=function(){    
                document.getElementById('team_data').innerHTML=this.responseText;
            }

            xhr.send('get_members');
        }

        function add_member(){
            let data=new FormData();
            data.append('name', member_name_input.value);
            data.append('picture', member_pic_input.files[0]);
            data.append('add_member', '');

            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);
            xhr.onload=function(){
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
                    alert('success', 'New member added!');
                    member_name_input='';
                    member_pic_input='';
                    get_members();
                }
            }

            xhr.send(data);
        }

        function remove_member(val){
            let xhr=new XMLHttpRequest();
            xhr.open("POST", "ajax/settings_crud.php", true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload=function(){  
                console.log(this.responseText);          
                if (this.responseText==1){
                    
                    alert('success', 'Member deleted!');
                    get_members();
                }    
                else{
                    alert('error', 'Server is currently down!');
                }
            }
            xhr.send('remove_member='+val);
        }

        window.onload=function(){
            get_general();
            get_contacts();
            get_members();
        }

        
        
    </script>

    <?php require('inc/scripts.php');?>
</body>
</html>