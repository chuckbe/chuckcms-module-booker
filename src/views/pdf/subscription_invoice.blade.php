<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>FACTUUR {{ ChuckModuleBooker::getSetting('invoice.prefix') . str_pad($subscription->json['invoice_number'], 4, '0', STR_PAD_LEFT) }}</title>
    <script src="https://use.fontawesome.com/6b5b6fae74.js"></script>
    <style>
      @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i');

      @page {
        margin: 2cm;
        background-color: #FFF;
      }

      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      a {
        color: #0072a5;
        text-decoration: none;
      }

      body {
        width: 18cm;
        height: 29.7cm;
        margin: 0 auto;
        color: #555555;
        background-color: #FFFFFF;
        font-family: Arial, sans-serif;
        font-size: 12px;
        font-family: 'Source Sans Pro', sans-serif;
      }

      header {
        padding: 10px 0;
        margin-bottom: 0px;
        display: block;
        height: 100px;
        text-align: center;
      }

      #logo {
        display:inline-block;
        margin-top: 8px;
        max-width: 70px;
      }

      #logo img {
        height: 30px;
      }

      #company {
        width: 415px;
        float:right;
        display:inline-block;
        text-align: right;
      }

      #client {
        padding-left: 6px;
        display:inline-block;
        width: 288px;
      }

      #details {
        display: block;
        height: 80px;
        margin-bottom: 30px;
      }

      #client .to {
        color: #777777;
      }

      h2.name {
        color: #0072a5;
        font-size: 1.6em;
        font-weight: normal;
        margin: 0;
      }

      #invoice {
        display: inline-block;
        max-width: 375px;
        text-align: right;
      }

      #invoice h1 {
        color: #0072a5;
        font-size: 1.7em;
        line-height: 1em;
        font-weight: bold;
        margin: 0  0 10px 0;
      }

      #invoice .date {
        font-size: 1.1em;
        color: #777777;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
      }

      table th,
      table td {
        padding: 3px 4px;
        background: #fff;
        text-align: center;
        border-bottom: 1px solid #000000;
      }
      table th {
        white-space: nowrap;
        font-weight: normal;
      }

      table td {
        text-align: center;
      }

      table td h3{
        color: #0072a5;
        font-size: 1em;
        font-weight: bold;
        margin: 0 0 0.2em 0;
      }

      table .no {
        color: #000;
        font-size: 1em;
        text-align: left;
      }

      table .desc {
        text-align: left;
      }

      table .unit {
        background: #fff;
      }

      table .qty {
      }

      table .total {
        color: #555;
        border-left: solid 1px #000;
      }

      table td.unit,
      table td.qty,
      table td.total {
        font-size: 1.1em;
      }


      table tfoot td {
        padding: 5px 5px;
        background: #FFFFFF;
        border-bottom: none;
        font-size: 1.1em;
        white-space: nowrap;
        border-top: 1px solid #000;
        color: #555;
      }

      table tfoot tr td:nth-child(2){
        text-align: left;
        border-right: solid 1px #000000
      }

      table tfoot tr:last-child td {
        font-size: 1.4em;
        color: #0072a5
      }

      table tfoot tr td:first-child {
        border: none;
      }

      span.verborgen{
        visibility: hidden;
      }
      span.beschrijving{
        font-size: .9em;
      }
      .color {
        color: #ff812b;
      }
      #thanks{
        font-weight: 400;
        font-size: 1.8em;
        margin-bottom: 10px;
        color: #0072a5;
      }

      #notices{
        padding-left: 6px;
        border-left: 6px solid #0072a5;
      }

      #notices .notice {
        font-size: 1.2em;
      }

      footer {
        color: #777777;
        width: 100%;
        height: 30px;
        font-size: 0.8em;
        bottom: 0;
        border-top: 1px solid #AAAAAA;
        padding: 8px 0;
        text-align: center;
        position: fixed;
      }
      .pagenum:before { content: counter(page); }
      .page-break {
        page-break-inside: avoid;
      }

      .pagebreaker{
        page-break-after:always;
      }

    </style>
  </head>
  <body>
    <header>
      <div style="width:auto;height:auto;margin: 0 auto;">
        <div id="logo">
          <img src="{{ asset(ChuckSite::getSetting('logo.href')) }}">
        </div>
        <div id="company">
          <h2 class="name" style="color:#000;">{{ ChuckSite::getSetting('company.name') }}</h2>
          <div>{{ ChuckSite::getSetting('company.vat') }}</div>
          <div>{{ ChuckSite::getSetting('company.street') .' '. ChuckSite::getSetting('company.housenumber') .', '. ChuckSite::getSetting('company.postalcode') .' '. ChuckSite::getSetting('company.city') }}</div>
          <div>{{ ChuckSite::getSetting('company.tel') }}</div>
          <div><a href="mailto:{{ ChuckSite::getSetting('company.email') }}">{{ ChuckSite::getSetting('company.email') }}</a></div>
        </div>
      </div>
    </header>

    <footer>
      {{ ChuckSite::getSetting('company.name') }} - {{ ChuckSite::getSetting('company.street') .' '. ChuckSite::getSetting('company.housenumber') .', '. ChuckSite::getSetting('company.postalcode') .' '. ChuckSite::getSetting('company.city') }}<br />{{ ChuckSite::getSetting('company.vat') }}<br />
      Onze algemene voorwaarden vind je terug op: {{ ChuckSite::getSetting('domain') }}/algemene-voorwaarden<br />
      Vragen bij deze factuur? Contacteer ons op {{ ChuckSite::getSetting('company.email') }}
      <p style="float:right;">Pagina <span style="color:#000;" class="pagenum"></span></p>
    </footer>

    <br />
    <main>
      <div id="details">
        <div style="width:auto;height:auto;margin: 0 auto;">
          <div id="client">
            <div class="to">FACTUUR VOOR:</div>
            <h2 class="name" style="color:#000;">{{ $subscription->customer->first_name .' '. $subscription->customer->last_name }}</h2>
            @if(!is_null($subscription->customer->tel))
            <div class="clientphone">TEL: {{ $subscription->customer->tel }}</div>
            @endif
            <div class="email"><a href="mailto:{{ $subscription->customer->email }}">{{ $subscription->customer->email }}</a></div>
          </div>
          <div id="invoice">
            <h1 style="color:#000;">FACTUUR {{ ChuckModuleBooker::getSetting('invoice.prefix') . str_pad($subscription->json['invoice_number'], 4, '0', STR_PAD_LEFT) }}</h1>
            <div class="date">datum: {{ date('d/m/Y', strtotime($subscription->created_at)) }}</div>
            <div class="date">Vervaldatum: {{ date('d/m/Y', strtotime($subscription->created_at)) }}</div>
          </div>
        </div>
      </div>
      <br><br>
    
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
          <th class="no">#</th>
          <th class="desc">BESCHRIJVING</th>
          <th class="qty">HVL.</th>
          <th class="qty">BTW %</th>
          <th class="unit fxwidth">BTW €<span class="verborgen">......</span></th>
          <th class="total">TOTAAL</th>
        </tr>
        </thead>


        <tbody>
            
            <tr>
              <td class="no">1</td>
              <td class="desc">
                <h3 style="color:#000;">{{ $subscription->subscription_plan->name }}</h3>
                @if($subscription->subscription_plan->type == 'one-off')
                <span class="beschrijving">Eenmalig (geldig tot: {{ $subscription->expires_at->format('d/m/Y H:i')}})</span>
                @elseif($subscription->subscription_plan->type == 'weekly')
                <span class="beschrijving">Wekelijks (geldig tot: {{ $subscription->expires_at->format('d/m/Y H:i')}})</span>
                @elseif($subscription->subscription_plan->type == 'monthly')
                <span class="beschrijving">Maandelijks (geldig tot: {{ $subscription->expires_at->format('d/m/Y H:i')}})</span>
                @elseif($subscription->subscription_plan->type == 'quarterly')
                <span class="beschrijving">Driemaandelijks (geldig tot: {{ $subscription->expires_at->format('d/m/Y H:i')}})</span>
                @elseif($subscription->subscription_plan->type == 'yearly')
                <span class="beschrijving">Jaarlijks (geldig tot: {{ $subscription->expires_at->format('d/m/Y H:i')}})</span>
                @endif
              </td>
              <td class="qty">1</td>
              <td class="qty">21%</td>
              <td class="unit">{{ ChuckModuleBooker::formatPrice(ChuckModuleBooker::taxFromPrice($subscription->price, 21)) }}</td>
              <td class="total">{{ ChuckModuleBooker::formatPrice($subscription->price) }}</td>
            </tr>
            
        </tbody>
        
        <tfoot class="page-break">
          <tr>  
            <td colspan="1"></td>
            <td colspan="4">SUBTOTAAL</td>
            <td>{{ ChuckModuleBooker::formatPrice(ChuckModuleBooker::priceWithoutTax($subscription->price, 21)) }}</td>
          </tr>
          
          
          <tr>  
            <td colspan="1"></td>
            <td colspan="4">BTW 21%</td>
            <td>{{ ChuckModuleBooker::formatPrice(ChuckModuleBooker::taxFromPrice($subscription->price, 21)) }}</td>
          </tr>
          
          <tr>
            <td colspan="1"></td>
            <td style="color:#000;" colspan="4">TOTAALPRIJS</td>
            <td style="color:#000;">{{ ChuckModuleBooker::formatPrice($subscription->price) }}</td>
          </tr>
        </tfoot>
      </table>
    

      <div class="page-break">
        <div style="color:#000;" id="thanks">Bedankt voor uw aankoop!</div>
        <div class="notice">
          
        </div>
        <br/><br/>
      </div>

    </main>
  </body>
</html>