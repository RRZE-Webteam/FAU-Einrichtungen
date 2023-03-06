// Define the scene and scroll events
var controller = new ScrollMagic.Controller();
var scene1 = new ScrollMagic.Scene({triggerElement: "#scene1", duration: 1500})
        .setPin("#scene1")
        .addTo(controller);
var scene2 = new ScrollMagic.Scene({triggerElement: "#scene2", duration: 1500})
        .setPin("#scene2")
        .addTo(controller);
var scene3 = new ScrollMagic.Scene({triggerElement: "#scene3", duration: 1500})
        .setPin("#scene3")
        .addTo(controller);


// Animate the text
var text = d3.select("#text");
var textPos = d3.select("#scene3").node().getBoundingClientRect();
var textCenter = [textPos.left + textPos.width / 2, textPos.top + textPos.height / 2];
scene3.on("progress", function (e) {
  var transform = "translate(" + (textCenter[0] - textPos.width / 2 * (1 - e.progress)) + "px," + (textCenter[1] - textPos.height / 2 * (1 - e.progress)) + "px)";
  text.style("transform", transform);
});

// Add debug indicators

