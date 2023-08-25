<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteru - Facilities</title>
    <?php require('inc1/links.php');?>
    <style>
        .pop:hover {
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }
    </style>
</head>

<body>
    <?php 
        require('inc1/header.php');
        
    ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
        <h4></h4>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Laboriosam nostrum explicabo veritatis, reiciendis reprehenderit est cumque quis. Provident laborum perspiciatis quos incidunt. Adipisci quidem sapiente, soluta explicabo rem nemo eos?
        </p>
    </div>
    <div class="container">
        <?php
            $res = select("SELECT * FROM `facility` WHERE `hotel`=?",['HTR'],'s');
            $path=FACILITY_IMG_PATH;
            $i = 0;
            while($row=mysqli_fetch_assoc($res)){
                if($i%2==0){
                    echo <<<data
                        <div class="row align-items-center bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                            <div class="col-md-12 col-lg-6 ml-auto order-lg-2 mb-5">
                                <img src="$path$row[picture]" alt="$row[name] image" class="img-fluid rounded">
                            </div>
                            <div class="col-md-12 col-lg-6 order-lg-1">
                                <img src="$path$row[icon]" alt="$row[name] svg" class="rounded" width="40px">
                                <h2>$row[name]</h2>
                                <p class="mb-4">$row[description]</p>
                            </div>
                        </div>
                    data;
                }
                else{
                    echo <<<data
                    <div class="row align-items-center bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                        <div class="col-md-12 col-lg-6 order-lg-2">
                            <img src="$path$row[icon]" alt="$row[name] svg" class="rounded" width="40px">
                            <h2>$row[name]</h2>
                            <p class="mb-4">$row[description]</p>
                        </div>
                        <div class="col-md-12 col-lg-6 ml-auto order-lg-1 mb-5">
                            <img src="$path$row[picture]" alt="$row[name] image" class="img-fluid rounded">
                        </div>
                    </div>
                data;
                }
                $i++;
            }
        ?>
    </div>
    <?php 
        require('inc1/footer.php');
    ?>

</body>

</html>