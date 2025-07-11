export default class Player{
    constructor(id, name,turn){
        this.id = id;
        this.name = name;
        this.score = 0;
        this.turn = turn;
        this.inGame = true;
    }
    addPoints(n){
        this.score +=n;
    }
    getScore(){
        return this.score;
    }
    getTurn(){
        return this.turn;
    }
    getName(){
        return this.name;
    }
    abandon(){
        this.inGame = false;
        this.score = 0;
    }  
    getInGame(){
        return this.inGame;
    }
}