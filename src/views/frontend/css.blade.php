<link rel="stylesheet" href="{{ asset('module-booker/css/splide.min.css') }}">
<link rel="stylesheet" href="{{ asset('module-booker/css/intlTelInput.min.css') }}">

<style>
.iti__flag {background-image: url("{{ ChuckSite::getSite('domain') }}/module-booker/img/flags.png");}

@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
.iti__flag {background-image: url("{{ ChuckSite::getSite('domain') }}/module-booker/img/flags@2x.png");}
}

.datepicker,
.table-condensed {
    width: 350px;
    height:350px;
}
.datepicker-inline {
    width: auto !important;
}
.bg-blue {
    background: navy;
}
.datepicker-days .table-condensed td.day {
    width: 60px;
    height: 50px;
    border-radius: 50%;
}

#splide .splide__slide {
    transition: background-color .3s ease,color .3s ease;
}

#splide .splide__slide.disabled {
    background-image: linear-gradient(135deg,#e5e5e5 16.67%,transparent 0,transparent 50%,#e5e5e5 0,#e5e5e5 66.67%,transparent 0,transparent);
    background-size: 4.24px 4.24px;
}

#splide .splide__slide.disabled span  {
    color: gray;
}
#splide .splide__slide.is_selected {
  background-color: #000;
  color: #fff;
}

.splide__arrow--prev {
    left: -3em;
}

.splide__arrow--next {
    right: -3em;
}

.splide__slide .day {

}
.splide__slide .number {
    font-size: 1.6rem;
}
.splide__slide .month {
    
}

.form-check {
    padding-left: .50rem;
}

label,
input[type="checkbox"] + span,
input[type="checkbox"] + span::before
{
    display: inline-block;
    vertical-align: middle;
}
 
label *,
label *
{
    cursor: pointer;
}
 

input[name="cmb_services"][type="checkbox"]
{
    opacity: 0;
    position: absolute;
    width: 0;
}
 

input[name="cmb_services"][type="checkbox"] + span
{
  
}
 
label:hover span::before
{
    -moz-box-shadow: 0 0 2px #ccc;
    -webkit-box-shadow: 0 0 2px #ccc;
    box-shadow: 0 0 2px #ccc;
}
 
label:hover span
{
    color: #000;
}

input[name="cmb_services"][type="checkbox"] + span::before
{
    content: "";
    width: 20px;
    height: 20px;
    margin: 0 12px 3px 0;
    border: solid 1px #a8a8a8;
    line-height: 17px;
    text-align: center;
     
    -moz-border-radius: 100%;
    -webkit-border-radius: 100%;
    border-radius: 100%;
}
 
input[name="cmb_services"][type="checkbox"]:checked + span::before
{
    color: #666;
   /* background-color: blue;*/
}
 
input[name="cmb_services"][type="checkbox"]:disabled + span
{
    cursor: default;
     
    -moz-opacity: .4;
    -webkit-opacity: .4;
    opacity: .4;
}
 
input[name="cmb_services"][type="checkbox"] + span::before
{
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    border-radius: 10px;
}
 
input[name="cmb_services"][type="checkbox"]:checked + span::before
{
    content: "\2713";
    font-size: 13px;
    color: #fff;
    background-color: #000000; /*#007bff;*/
    padding-left: 1px;
}

.checkbox-type .far {
  transition: .3s transform ease-in-out;
  transform: rotate(180deg);
}
.checkbox-type .collapsed .far {
  transform: rotate(0deg);
}

.checkbox-type .description {
  padding-left: 2rem;
}
.disabled {
    pointer-events: none;
}
.font-size-small {
    font-size: 0.9rem!important;
}
.cmb_datepicker_timeslot > span {
    cursor: pointer;
}
.cmb_datepicker_timeslot > span.is_selected {
    background: #F0F0F0;
}
</style>