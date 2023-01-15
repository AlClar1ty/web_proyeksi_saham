@extends('layouts.app')

@section('style')
  <style>
    img{
        max-height: 2em;
    }
  </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="text-center font-weight-bold m-0" id="time"></h1>
                </div>

                <div class="card-body">
                    <div class="row mb-2 mx-5">   
                        <form id="addForm" class="registration-form col-md-6 pl-0" action="" method="GET">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="search" value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}" placeholder="Search Company" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-secondary" type="button">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table">
                            <thead style="background-color: lavender; font-weight: 800;">
                                <tr>
                                    <td class="text-center">Logo</td>
                                    <td class="text-center">Company Name</td>
                                    <td class="text-center">View</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $companyNya)
                                    <tr>
                                        <td><img src="{{ $companyNya['logo'] }}"></td>
                                        <td>{{ $companyNya['name'] }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('show', ['company' => $companyNya['id']]) }}" target="_blank" method="GET">
                                                <button type="submit" name="typeNya" value="edit" class="btn py-0 ml-1">
                                                    <i class="mdi mdi-eye" style="font-size: 1.3em; color: blue;"></i>
                                                </button>
                                            </form>                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <?php echo $companies->appends($url)->links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script>
    function clearInvalid(keyNya) {
        $("#addForm").find("input[name="+keyNya+"]").next().find("strong").text("");
    }
</script>
<script type="text/javascript">
    window.onload = setInterval(clock, 1000);
    function clock()
    {
        var d = new Date();

        var date = d.getDate();
        var month = d.getMonth();
        var montharr = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des"];
        month = montharr[month];

        var day = d.getDay();
        var dayarr = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        day = dayarr[day];

        var year = day + " " + date + " " + month + " " + d.getFullYear();

        var hour = d.getHours();
        var min = d.getMinutes();
        var sec = d.getSeconds();
        document.getElementById("time").innerHTML = year + ", " + (hour < 10 ? '0' : '') + hour + ":" + (min < 10 ? '0' : '') + min + ":" + (sec < 10 ? '0' : '') + sec;
    }
</script>
@endsection
