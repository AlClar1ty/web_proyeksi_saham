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
                    <div class="row mb-2 mx-5 border-bottom p-3">
                        <div class="col-2 d-flex">
                            <img class="my-auto mx-auto" src="{{ $company['logo'] }}">
                        </div>
                        <div class="col-10">
                            <h3 class="text-left m-0 font-weight-bold">{{ $company['name'] }}</h3>
                            <p class="text-left m-0">IDX: {{ $company['ticker'] }}</p>
                        </div>
                    </div>

                    <div class="row mb-2 mx-5 border-bottom p-3">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 text-right pr-2">
                                    <h4 class="">Close:</h4>
                                    <p class="m-0">Change:</p>
                                    <p class="">Updated at:</p>
                                </div>
                                <div class="col-8 pl-0">
                                    <h4 class="text-left mb-2 d-inline">{{ number_format($company->price->last()['close'], 2, ".", ",") }}</h4>
                                    <p class="text-left d-inline">IDR</p>
                                    <p class="mt-2 mb-0 {{ $company->price->last()['close'] - $company->price->last()['open'] < 0 ? "text-danger" : "text-success"}}">
                                        {{ number_format($company->price->last()['close'] - $company->price->last()['open'], 2, ",", ".") }} IDR
                                        ({{ ($company->price->last()['close'] - $company->price->last()['open']) != 0 ? number_format(($company->price->last()['close'] - $company->price->last()['open'])/$company->price->last()['open']*100, 2, ".", "") : 0 }}%)
                                        @if($company->price->last()['close'] - $company->price->last()['open'] < 0)
                                            <i class="mdi mdi-arrow-down-bold" style="font-size: 1em;"></i>
                                        @else
                                            <i class="mdi mdi-arrow-up-bold" style="font-size: 1em;"></i>
                                        @endif
                                        hari ini
                                    </p>
                                    <p class="mb-0">{{ $company->price->last()['updated_at'] }}</p>
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="col-6">
                            <div class="row">
                                <div class="col-4 text-right pr-2">
                                    <p class="m-1">Open:</p>
                                    <p class="m-1">High:</p>
                                    <p class="m-1">Low:</p>
                                    <p class="m-1">Volume:</p>
                                </div>
                                <div class="col-8 pl-0">
                                    <p class="m-1">{{ number_format($company->price->last()['open'], 2, ".", ",") }} IDR</p>
                                    <p class="m-1">{{ number_format($company->price->last()['high'], 2, ".", ",") }} IDR</p>
                                    <p class="m-1">{{ number_format($company->price->last()['low'], 2, ".", ",") }} IDR</p>
                                    <p class="m-1">{{ number_format($company->price->last()['volume'], 0, ".", ",") }}</p>
                                </div>
                            </div>                            
                        </div>
                    </div>

                    <div class="row mb-2 mx-5 border-bottom p-3">
                        <table class="table">
                            <thead style="background-color: lavender; font-weight: 800;">
                                <tr>
                                    <td class="text-left">Date</td>
                                    <td class="text-center">Open</td>
                                    <td class="text-center">High</td>
                                    <td class="text-center">Low</td>
                                    <td class="text-center">Close</td>
                                    <td class="text-center">Change</td>
                                    <td class="text-right">Volume</td>

                                </tr>
                            </thead>
                            <tbody>
                                @php $firstPrice = null; @endphp
                                @foreach($company->price as $keyNya => $priceNya)
                                    <tr>
                                        @php
                                            if($firstPrice == null){
                                                $changeNya = number_format($priceNya['close'] - $priceNya['open'], 2, ",", ".");
                                                $classStyle = $changeNya < 0 ? "text-danger" : ($changeNya > 0 ? "text-success" : "text-primary");
                                                $percentageNya = 0;
                                                if($priceNya['close'] - $priceNya['open'] != 0){
                                                    $percentageNya = number_format(($priceNya['close'] - $priceNya['open'])/$priceNya['open']*100, 2, ".", "");
                                                }
                                                $firstPrice = $priceNya['close'];
                                            }
                                            else{
                                                $changeNya = number_format($priceNya['close'] - $firstPrice, 2, ",", ".");
                                                $classStyle = $changeNya < 0 ? "text-danger" : ($changeNya > 0 ? "text-success" : "text-primary");
                                                $percentageNya = 0;
                                                if($priceNya['close'] - $firstPrice != 0){
                                                    $percentageNya = number_format(($priceNya['close'] - $firstPrice)/$firstPrice*100, 2, ".", "");
                                                }
                                                $firstPrice = $priceNya['close'];
                                            }
                                            if($keyNya == 0){
                                                continue;
                                            }
                                        @endphp

                                        <td>{{ date('d/m/Y H:i', strtotime($priceNya['created_at'])) }}</td>
                                        <td class="text-center">{{ $priceNya['open'] }} IDR </td>
                                        <td class="text-center">{{ $priceNya['high'] }} IDR </td>
                                        <td class="text-center">{{ $priceNya['low'] }} IDR </td>
                                        <td class="text-center">{{ $priceNya['close'] }} IDR </td>
                                        <td class="text-center {{ $classStyle }}">
                                            {{ $changeNya }} IDR 
                                            @if($changeNya < 0)
                                                <i class="mdi mdi-arrow-down-bold" style="font-size: 1em;"></i>
                                            @elseif($changeNya > 0)
                                                <i class="mdi mdi-arrow-up-bold" style="font-size: 1em;"></i>
                                            @else
                                                <i class="mdi mdi-equal" style="font-size: 1em;"></i>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $priceNya['volume'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-2 mx-5 border-bottom p-3">
                        <div class="col-12">
                            <h5 class="mb-1 font-weight-bold">Description: </h5>
                            <p>{{ $company['description'] }}</p>

                            <h5 class="mb-1 font-weight-bold">Sector: </h5>
                            <p>{{ $company['sector'] }}</p>     

                            <h5 class="mb-1 font-weight-bold">Email: </h5>
                            <p>{{ $company['email'] }}</p>       

                            <h5 class="mb-1 font-weight-bold">Phone: </h5>
                            <p>{{ $company['phone'] }}</p>       

                            <h5 class="mb-1 font-weight-bold">Address: </h5>
                            <p>{{ $company['address'] }}</p>     

                            <h5 class="mb-1 font-weight-bold">Website: </h5>
                            <p><a href="{{ $company['website'] }}">{{ $company['website'] }}</a></p>                    
                        </div>
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
