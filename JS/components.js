//components

var sliders = document.getElementsByClassName("slider")
var outputs = document.getElementsByClassName("outputSlider")

for(let i =0; i < sliders.length; i++)
{
	// sliders[i].addEventListener("load", slidersLoad )

	var slider = sliders[i]
	var output = outputs[i]
    output.innerHTML = slider.value;
    
    slider.oninput = function() {
      output.innerHTML = this.value;
    }
}

function slidersLoad()
{
    // var sliders = document.getElementsByClassName("slider");
    var slider = sliders[0]

}




