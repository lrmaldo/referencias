<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Title -->
    <title>Mi Pago</title>

    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C500%2C600%2C700">

    <link rel="stylesheet" href="css/bootstrap.min.css?v=3">
    <link rel="stylesheet" href="css/unify.css?v=3">
    <link rel="stylesheet" href="css/font-awesome.min.css?v=3">
    <link rel="stylesheet" href="css/estilo_vic.css">
  </head>

  <body>
    <main>
      <!-- Login -->
      <section class="container">
        <div class="row justify-content-center">
          <div class="col-sm-8 col-lg-5">
            <div class="g-brd-around g-brd-gray-light-v4 rounded g-py-40 g-px-30">
              <header class="text-center mb-4"><img src="images/logo_vic.png">
              </header>
              <!-- Form -->
              
                <div class="mb-4">
                  <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-13">Num. de Cliente:</label>
                  <input class="form-control" type="text" placeholder="Num. de cliente" id="idCliente" onkeypress="javascript:performSearch(event)">
                </div>
                <div class="col-12 align-self-center">
                  <button class="btn btn-md u-btn-primary rounded g-py-13 g-px-25" id="btnSearch" type="button" onclick="javascript:performRequest()"><i class="fa fa-sign-in fa-lg"></i> Consultar</button>
                </div>
              
              <!-- End Form -->

            </div>
          </div>
        </div>
		</section>
		<hr>
		<section class="container ">
			<div class="row justify-content-center">
				<div class="col-lg-12" id="resultsDiv">
		            
				</div>
			</div>
      </section>

      
    </main>

    <!-- JS Global Compulsory -->
    
    <script src="js/tether.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/mainFunctions.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>