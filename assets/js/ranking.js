document.addEventListener("DOMContentLoaded", () => {
  uploadDOM();
  function uploadDOM() {
    var peticion = new XMLHttpRequest();
    const params = new URLSearchParams(window.location.search);
    const size = params.get("selectSize");
    peticion.open("GET", "../php/getRanking.php?selectSize=" + size, true);
    peticion.onreadystatechange = function () {
      console.log(peticion.responseText);
      var count = 1;
      if (peticion.readyState === 4 && peticion.status === 200) {
        var ranking = JSON.parse(peticion.responseText);
        var content = document.getElementById("content");

        var rowHeader = document.createElement("div");
        var nameHeader = document.createElement("span");
        var gamesWonHeader = document.createElement("span");
        var sizeHeader = document.createElement("span");

        rowHeader.className = "rowRankingHeader";
        nameHeader.className = "nameHeader";
        sizeHeader.className = "sizeHeader";
        gamesWonHeader.className = "gamesHeader";

        nameHeader.textContent = "Nombre";
        sizeHeader.textContent = "Tablero " + size +" x "+size;
        gamesWonHeader.textContent = "Juegos ganados";

        rowHeader.appendChild(nameHeader);
        rowHeader.appendChild(sizeHeader);
        rowHeader.appendChild(gamesWonHeader);
        content.appendChild(rowHeader);

        ranking.forEach((user) => {
          var row = document.createElement("div");
          var name = document.createElement("span");
          name.textContent = user.username;
          var gamesWon = document.createElement("span");
          gamesWon.textContent = user.gameswon;
          var sizeRow = document.createElement("span");
          sizeRow.textContent = size + "x" + size;
          var medal = document.createElement("span");

          if (count === 1 && user.gameswon !=0) {
            medal.textContent = "🥇";
            medal.className = "medal gold";
          } else if (count === 2 && user.gameswon !=0) {
            medal.textContent = "🥈";
            medal.className = "medal silver";
          } else if (count === 3 && user.gameswon !=0) {
            medal.textContent = "🥉";
            medal.className = "medal bronze";
          } else {
            medal.textContent = "";
            medal.className = "medal"; 
          }
          if (count < 5 && user.gameswon !=0
) {
          row.className = "rowRankinFive";
            name.className = "nameRankingFive";
            gamesWon.className = "gamesRankinFive";
            sizeRow.className = "sizeRankingFive";

       

          } else {
            row.className = "rowRankin";
            name.className = "nameRanking";
            gamesWon.className = "gamesRankin";
            sizeRow.className = "sizeRanking";
          }

          row.appendChild(name);
          row.appendChild(medal);
          row.appendChild(gamesWon);
          content.appendChild(row);
          count++;
        });
      }
    };
    peticion.send();
  }
});