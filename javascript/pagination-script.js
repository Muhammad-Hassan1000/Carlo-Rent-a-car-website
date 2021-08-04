const rows = document.querySelector(".rows").children;
const prev = document.querySelector(".prev");
const next = document.querySelector(".next");
const page = document.querySelector(".page-num");
const maxItem = 12;
let index = 1;

const pagination = Math.ceil(rows.length/maxItem);

prev.addEventListener("click", function(){
    if(index > 1){
        index--;
        window.scrollTo(0,200);
    }
    else{
        index = 1;
    }
    showItems();
})
next.addEventListener("click", function(){
    if(index < pagination){
        index++;
        window.scrollTo(0,200);
    }
    else{
        index = pagination;
    }
    showItems();
})


function showItems(){
    for(let i=0; i < rows.length; i++){
        rows[i].classList.remove("show");
        rows[i].classList.add("hide");
        
        if(i >= (index * maxItem) - maxItem && i < (index * maxItem)){
            rows[i].classList.remove("hide");
            rows[i].classList.add("show");
        }
        page.innerHTML = index;
    }

}

window.onload = function(){
    showItems();
    check();
}