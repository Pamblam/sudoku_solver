
class SudokuSolver{
	constructor(renderer, board){
		this.renderer = renderer;
		this.board = board;
		this.possibilities = [];
		this.divisors = [];
		renderer.draw(board);
		this.generateCellPossibilities();
		this.calculateDivisors();
	}
	calculateDivisors(){
		for (var i = this.possibilities.length - 1; i >= 0; i--) {
			this.divisors[i] = this.divisors[i + 1] ? this.divisors[i + 1] * this.possibilities[i + 1].length : 1;
		}
	}
	getSubgridPosFromCell(x, y){
		var getPos = n=>n<3?0:n>2&&n<6?1:2;
		return [getPos(x), getPos(y)];
	}
	getCellsInSubgrid(x, y){
		var getPos = n=>n===0?[0,1,2]:n===1?[3,4,5]:[6,7,8];
		var xs = getPos(x);
		var ys = getPos(y);
		return xs.map(x=>ys.map(y=>[x,y])).flat();
	}
	getNumbersInSubgrid(x, y, board){
		if(!board) board = this.board;
		return this.getCellsInSubgrid(x, y)
			.map(coords=>board[coords[1]][coords[0]])
			.filter(value=>value!==null);
	}
	getNumbersInRow(y){
		return this.board[y].filter(value=>value!==null);
	}
	getNumbersInColumn(x){
		return this.board.map(row=>row[x]).filter(value=>value!==null);
	}
	getPossibleNumbersForCell(x, y){
		var used = [], avail = [];
		var subgridPosition = this.getSubgridPosFromCell(x, y);
		used.push(...this.getNumbersInSubgrid(subgridPosition[0], subgridPosition[1]));
		used.push(...this.getNumbersInColumn(x));
		used.push(...this.getNumbersInRow(y));
		for(var i=1; i<10; i++) if(!~used.indexOf(i)) avail.push(i);
		return avail;
	}
	generateCellPossibilities(){
		for(var y=0; y<9; y++){
			for(var x=0; x<9; x++){
				this.possibilities.push(null === this.board[y][x] ? 
					this.getPossibleNumbersForCell(x, y):
					[this.board[y][x]]);
			}
		}
	}
	getPermutation(n) {
		var result = "", curArray;
		for (var i = 0; i < this.possibilities.length; i++) {
			curArray = this.possibilities[i];
			result += curArray[Math.floor(n / this.divisors[i]) % curArray.length];
		}
		return result.match(/.{1,9}/g).map(row=>row.split(''));
	}
	validateSubgrid(x, y, board){
		var numbers = getNumbersInSubgrid(x, y, board);
		return [...new Set(numbers)].length === 9;
	}
	validateBoard(board){
		for(var y=0; y<3; y++){
			for(var x=0; x<3; x++){
				if(!validateSubgrid(x, y, board)){
					console.log("subgrid", x, y, "is not valid");
					return false;
				}
			}
		}
		console.log("subgrids valid, check rows and columns");
	}
}