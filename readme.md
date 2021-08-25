# Booking module for ChuckCMS 

### Usage

- After installing and publishing the config file, fill in the required details
- Create a page for the order form in ChuckCMS and use a custom template file
- Inside that custom template file you can use the following method to call the necessary files
``` 
//use this to load css and styles
{!! ChuckModuleBooker::renderStyles() !!}

//use this to load js and scripts
{!! ChuckModuleBooker::renderScripts() !!}

//use this to load the form itself - do not wrap it in a container
{!! ChuckModuleBooker::renderForm() !!}