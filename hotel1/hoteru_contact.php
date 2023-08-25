<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteru - Contact</title>
    <?php require('inc1/links.php');?>
</head>

<body>
    <?php 
        require('inc1/header.php');
        
    ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Laboriosam nostrum explicabo veritatis, reiciendis reprehenderit est cumque quis. Provident laborum perspiciatis quos incidunt. Adipisci quidem sapiente, soluta explicabo rem nemo eos?
        </p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1500532554965!2d106.70873760894564!3d10.799817258720827!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528baac1c1979%3A0x6b3506ef0ded73fa!2zMjY2IFjDtCBWaeG6v3QgTmdo4buHIFTEqW5oLCBQaMaw4budbmcgMjEsIELDrG5oIFRo4bqhbmgsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCwgVmlldG5hbQ!5e0!3m2!1sen!2s!4v1687137580414!5m2!1sen!2s" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <h5>Address</h5>
                    <a href="<?php echo $contact_r['gmap']?>" target="_blank" class="d-inline-block text-decoraton-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i><?php echo $contact_r['address']?>
                    </a>
                    <h5 class="mt-4">Call us</h5>
                    <a href="<?php echo $contact_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i>+<?php echo $contact_r['pn1']?>
                    </a>
                    <br>
                    <?php
                        if($contact_r['pn2']!=''){
                            echo <<<data
                                <a href="tel: +$contact_r[pn2]>" class="d-inline-block mb-2 text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i>+$contact_r[pn2]
                                </a>
                            data;
                        }
                    ?>
                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: <?php echo $contact_r['email']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope-at-fill"></i><?php echo $contact_r['email']?>
                    </a>
                    <h5 class="mt-4">Follow us</h5>
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
                        if($contact_r['ins']!=''){
                            echo <<<data
                                <a href="$contact_r[ins]" class="d-inline-block mb-1">
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
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>Send us a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Message</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
    <?php
        if(isset($_POST['send'])){
            $frm_data = filteration($_POST);
            $q = "INSERT INTO `user_note`(`name`, `email`, `subject`, `message`, `send_to`) VALUES (?,?,?,?,?)";
            $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message'],'HTR'];
            $res = insert($q,$values,'sssss');
            if($res==1){
                alert('success','Mail sent!');
            }
            else{
                alert('error','Server Down! Try again later.');
            }
        }
    ?>
    <?php 
        require('inc1/footer.php');
    ?>

</body>

</html>