<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>

<body>



<p> image </p>
<img src="{{ asset('apple.jpg') }}" width="70px" height="70px" >

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">name</th>
      <th scope="col">image</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($banner as $key=> $Banner )
    <tr>
      <th scope="row">1</th>
     
      <td> <img src="{{ asset($Banner->image) }}" width="70px" height="70px" > </td>
    
      
    </tr>
   @endforeach
  </tbody>
</table>

</body>
</htm>