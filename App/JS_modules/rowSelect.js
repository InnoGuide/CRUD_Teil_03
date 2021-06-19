export function rowSelect(){
    let table= document.querySelector(".table");
table.rows[1].classList.add("bg-primary","text-white");
console.log(table.rows);
document.querySelectorAll(".t-row").forEach(elem=>{
    elem.addEventListener('click', row=>{
       const rowSelect=document.querySelector(".t-row.bg-primary.text-white");
       rowSelect.classList.remove("bg-primary","text-white");
       row.currentTarget.classList.add("bg-primary","text-white");
    })
})
document.addEventListener("keydown", event=>{
    if(event.key!=="ArrowDown" && event.key!=="ArrowUp") return;
    const rowSelect=document.querySelector(".t-row.bg-primary.text-white");
    switch(event.key){
        case "ArrowDown":
             if(rowSelect.rowIndex+1<table.rows.length){
                event.preventDefault();
                rowSelect.classList.remove("bg-primary","text-white");
                table.rows[rowSelect.rowIndex+1].classList.add("bg-primary","text-white");
                if (!isElementInViewport(table.rows[rowSelect.rowIndex+2])){
                    table.rows[rowSelect.rowIndex+1].scrollIntoView({block: "end"}); 
                }
             }
        break;
        case "ArrowUp":
            if(rowSelect.rowIndex-1>0){
                event.preventDefault();
                rowSelect.classList.remove("bg-primary","text-white");
                table.rows[rowSelect.rowIndex-1].classList.add("bg-primary","text-white");
                if (!isElementInViewport(table.rows[rowSelect.rowIndex-1])){
                    table.rows[rowSelect.rowIndex-1].scrollIntoView(); 
                }
             }
    }

} )
 
 
}

function isElementInViewport(el) {
    let rect = el.getBoundingClientRect();
    return (
         rect.top >= 0 &&
         rect.left >= 0 &&
         rect.bottom <= (window.innerHeight || document. documentElement.clientHeight) &&
         rect.right <= (window.innerWidth || document. documentElement.clientWidth)
       );
 }