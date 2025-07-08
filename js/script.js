const items = document.querySelectorAll('.container .elements');
const next = document.getElementById("next");
const prev = document.getElementById("prev");

console.log('hello');

let current = 5;

function slider(){

let sides = 0;

items[current].style.transform = `none`
items[current].style.zindex = 1;
items[current].style.filter = "none";
items[current].style.opacity = 1;

for(var i = current+1; i < items.length; i++){

sides++;
items[i].style.transform = `translateX(${220 * sides}px) scale(${1 - 0.2})`;
items[i].style.zindex = -1;
items[i].style.filter = "blur(5px)";
items[i].style.opacity = 0.2;

}

sides = 0;

for(var i = current-1; i >= 0; i--){

sides--;
items[i].style.transform = `translateX(${220 * sides}px) scale(${1 - 0.2})`;
items[i].style.zindex = -1;
items[i].style.filter = "blur(5px)";
items[i].style.opacity = 0.2;

}

}

slider();

next.onclick = function(){

current = current+1 < items.length ? current + 1 : current = 0;
slider();

}

prev.onclick = function(){

current = current-1 >= 0 ? current - 1 : current = 23;
slider();
    
}

document.addEventListener('keydown', function(e){

if(e.key === 'ArrowLeft'){

    current = current-1 >= 0 ? current - 1 : current = 23;
    slider();

}
else if(e.key === 'ArrowRight'){

    current = current+1 < items.length ? current + 1 : current = 0;
    slider();

}

})