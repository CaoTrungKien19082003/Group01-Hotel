    <div class="container-fluid bg-white mt-5">
        <div class="row">
            <div class="col-lg-4">
                <h3 class="h-font fw-bold fs-3 mb-2">Hoteru</h3>
                <p>lorem 20</p>
            </div>
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
                <br>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script>
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
        setActive();
    </script>
    