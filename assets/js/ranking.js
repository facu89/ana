document.addEventListener("DOMContentLoaded",  () => {

    uploadDOM();
    function uploadDOM(){
        var peticion = new XMLHttpRequest();
        peticion.open("GET", "../php/getRanking.php", true);
        peticion.onreadystatechange = function() {
            console.log(peticion.responseText);
            var count = 1;
            if (peticion.readyState === 4 && peticion.status === 200) {
                var ranking = JSON.parse(peticion.responseText);
                var  content = document.getElementById("content");
                ranking.forEach(user => {

                    var row = document.createElement('div');
                    var name = document.createElement('span');
                    name.textContent = user.username;
                    var gamesWon = document.createElement('span');
                    gamesWon.textContent = user.gameswon;
                    if(count > 5){
                        row.className = 'rowRankinFive';
                        name.className = 'nameRankingFive';
                        gamesWon.className = 'gamesRankinFive';
                    }
                    else{
                        row.className = 'rowRankin';
                        name.className = 'nameRanking';
                        gamesWon.className = 'gamesRankin';
                    }
                    row.appendChild(name);
                    row.appendChild(gamesWon);
                    content.appendChild(row);
                    count++; 

                });
            }
        };
        peticion.send()

    }
});