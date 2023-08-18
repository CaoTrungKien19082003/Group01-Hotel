<div class="container-fluid bg-white mt-5">
        <div class="row">
            <?php
            echo <<< data
            <div class="col-lg-4">
                <h3 class="h-font fw-bold fs-3 mb-2">$settings_r[site_title]</h3>
                <p>$settings_r[site_about]</p>
            </div>
            data;
            ?>
            <div class="col-lg-4">
                <h5 class="mb-3">Links</h5>
                <a href="index.php" class="d-inline-block text-dark text-decoration-none">Home</a><br>
                <a href="rooms.php" class="d-inline-block text-dark text-decoration-none">Rooms</a><br>
                <a href="facilities.php" class="d-inline-block text-dark text-decoration-none">Facilities</a><br>
                <a href="about.php" class="d-inline-block text-dark text-decoration-none">About us</a><br>
                <a href="contact.php" class="d-inline-block text-dark text-decoration-none">Contact</a><br>
            </div>
            <div class="col-lg-4">
                <h5 class="mb-3">Follow us</h5>
                <?php
                    if($contact_r['tw']!=''){
                        echo <<<data
                            <a href="$contact_r[tw]" class="d-inline-block mb-1">
                                <span class="badge bg-light text-dark fs-6 p-2">
                                    <i class="bi bi-twitter me-1"></i>Twitter
                                </span>
                            </a>
                        data;
                    }
                ?>
                <br>
                <?php
                    if($contact_r['insta']!=''){
                        echo <<<data
                            <a href="$contact_r[insta]" class="d-inline-block mb-1">
                                <span class="badge bg-light text-dark fs-6 p-2">
                                    <i class="bi bi-instagram me-1"></i>Instagram
                                </span>
                            </a>
                        data;
                    }
                ?>
                <br>
                <?php
                    if($contact_r['fb']!=''){
                        echo <<<data
                            <a href="$contact_r[tw]" class="d-inline-block mb-1">
                                <span class="badge bg-light text-dark fs-6 p-2">
                                    <i class="bi bi-facebook me-1"></i>Facebook
                                </span>
                            </a>
                        data;
                    }
                ?>
                <br>
                <?php
                    if($contact_r['tt']!=''){
                        echo <<<data
                            <a href="$contact_r[tt]" class="d-inline-block mb-1">
                                <span class="badge bg-light text-dark fs-6 p-2">
                                    <i class="bi bi-tiktok me-1"></i>Tiktok
                                </span>
                            </a>
                        data;
                    }
                ?>
                <br>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script>
        function alert(type,msg,position='body'){
             let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
             let element =document.createElement('div');
             element.innerHTML = `
                <div class="alert ${bs_class} alert-dismissible fade show custom-alert" role="alert">
                        <strong class="me-3">${msg}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            if (position=='body'){
                document.body.append(element);
                element.classList.add('custom-alert');
            }
            else{
                document.getElementById(position).appendChild(element);
            }
            setTimeout(remAlert,3000);
        }
        function remAlert(){
            document.getElementsByClassName('alert')[0].remove();
        }
        function setActive(){
            let navbar = document.getElementById('nav-bar');
            let a_tag = navbar.getElementsByTagName('a');
            for(i=0;i<a_tag.length;i++){
                let file =a_tag[i].href.split('/').pop();
                let file_name = file.split('.')[0];
                if(document.location.href.indexOf(file_name)>=0){
                    a_tag[i].classList.add('active');
                }
            }
        }
        let register_form = document.getElementById('register-form');
        register_form.addEventListener('submit',(e)=>{
            e.preventDefault();
            let data= new FormData();
            data.append('name',register_form.elements['name'].value);
            data.append('email',register_form.elements['email'].value);
            data.append('phone',register_form.elements['phone'].value);
            data.append('profile',register_form.elements['profile'].files[0]);
            data.append('user_id',register_form.elements['user_id'].value);
            data.append('add',register_form.elements['add'].value);
            data.append('pcode',register_form.elements['pcode'].value);
            data.append('dob',register_form.elements['dob'].value);
            data.append('pass',register_form.elements['pass'].value);
            data.append('cpass',register_form.elements['cpass'].value);
            data.append('register','');
            var myModal = document.getElementById('registerModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/login_register.php", true);
            xhr.onload = function(){
                if(this.responseText == 'pass_mismatch'){
                    alert('error',"Password Mismatch!");
                }else if(this.responseText == 'email_already'){
                    alert('error',"Email is already registered!");
                }else if(this.responseText == 'phone_already'){
                    alert('error',"Phone number is already registered!");
                }else if(this.responseText == 'inv_img'){
                    alert('error',"Only JPG, WEBP & PNG images are allowed!");
                }else if(this.responseText == 'upd_failed'){
                    alert('error',"Image upload failed!");
                }else if(this.responseText == 'mail_failed'){
                    alert('error',"Cannot send confirmation email! Server down!");
                }else if(this.responseText == 'ins_failed'){
                    alert('error',"Registration failed! Server down!");
                }else{
                    alert('success','Registration success! Please check the mail!');
                    register_form.reset();
                }
            }
            xhr.send(data);
        });
        let login_form = document.getElementById('login-form');
        login_form.addEventListener('submit',(e)=>{
            e.preventDefault();
            let data= new FormData();

            data.append('email',login_form.elements['email'].value);
            data.append('password',login_form.elements['password'].value);
            data.append('login','');
            //alert('error',data.get('password'));
            var myModal = document.getElementById('loginModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/login_register.php", true);
            xhr.onload = function(){
                
                if(this.responseText == 'inv_email'){
                    alert('error',"Invalid Email!");
                }else if(this.responseText == 'not_verified'){
                    alert('error',"Email is not verified!");
                }else if(this.responseText == 'inactive'){
                    alert('error',"Account Suspended! Please contact the Admin!");
                }else if(this.responseText == 'invalid_pass'){
                    alert('error',"Password is not correct!");
                }else{
                    window.location = window.location.pathname;
                }
            }
            xhr.send(data);
        });
        let passf_form = document.getElementById('passf-form');
        passf_form.addEventListener('submit',(e)=>{
            e.preventDefault();
            let data= new FormData();
            data.append('email',passf_form.elements['email'].value);
            data.append('passf','');
            //alert('error',data.get('password'));
            var myModal = document.getElementById('passfModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/login_register.php", true);
            xhr.onload = function(){
                if(this.responseText == 'inv_email'){
                    alert('error',"Invalid Email!");
                }else if(this.responseText == 'not_verified'){
                    alert('error',"Email is not verified!");
                }else if(this.responseText == 'inactive'){
                    alert('error',"Account Suspended! Please contact the Admin!");
                }else if(this.responseText == 'mail_failed'){
                    alert('error',"Cannot send email, Server down!");
                }else if(this.responseText == 'upd_failed'){
                    alert('error',"Password update failed, Server down!");
                }else{
                    alert('success',"Reset link sent to your email");
                    passf_form.reset();
                }
            }
            xhr.send(data);
        });
        setActive();
    </script>
    <h6 class="text-center bg-dark text-white mg-3">Designed idea by TJ WEBDEV, Developed and testing by Red Axolotl(Cao Trung Kiên) </h6>