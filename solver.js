
class SudokuSolver{
	constructor(renderer, board){
		this.renderer = renderer;
		this.original_board = board.slice();
		this.board = board.slice().map(row=>row.slice());
		this.row = 0;
		this.col = 0;
		this.draw(board);
		this.permutation = 0;
		this.startTime;
		this.completeTime;
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
	getNumbersInSubgrid(x, y){
		return this.getCellsInSubgrid(x, y)
			.map(coords=>this.board[coords[1]][coords[0]])
			.filter(value=>value!==null);
	}
	getNumbersInRow(y){
		return this.board[y].filter(value=>value!==null);
	}
	getNumbersInColumn(x){
		return this.board.map(row=>row[x]).filter(value=>value!==null);
	}
	validateSubgrid(col, row){
		var gridPos = this.getSubgridPosFromCell(col, row);
		var grid = this.getNumbersInSubgrid(gridPos[0], gridPos[1]);
		return grid.length === [...new Set(grid)].length;
	}
	validateRow(y){
		var row = this.board[y].filter(m=>m!==null);
		return row.length === [...new Set(row)].length;
	}
	validateCol(x){
		var col = this.board.map(row=>row[x]).filter(m=>m!==null);
		return col.length === [...new Set(col)].length;
	}
	isFixedNumber(x, y){
		return this.original_board[y][x] !== null;
	}
	advance(){
		if(this.col === 8){
			this.col = 0;
			this.row++;
		}else this.col++;
		if(this.isFixedNumber(this.col, this.row)){
			this.advance();
		}
	}
	rewind(){
		if(!this.isFixedNumber(this.col, this.row)){
			this.board[this.row][this.col] = null;
		}
		if(this.col === 0){
			this.col = 8;
			this.row--;
		}else this.col--;
		if(this.isFixedNumber(this.col, this.row)){
			this.rewind();
		}
	}
	increment(){
		this.board[this.row][this.col] = (this.board[this.row][this.col]||0)+1;
		if(this.board[this.row][this.col] === 10){
			this.rewind();
			this.increment();
		}
	}
	validateCurrentCell(){
		return this.validateRow(this.row) && this.validateCol(this.col) && this.validateSubgrid(this.col, this.row);
	}
	checkNext(draw){
		if(null === this.board[this.row][this.col]) this.increment();
		if(this.validateCurrentCell()){ 
			this.draw();
			if(this.col === 8 && this.row === 8) return true;
			this.advance();
			this.permutation++;
			if(draw) this.draw();
		}else{
			this.increment();
			this.checkNext();
		}
		return false;
	}
	draw(){
		if(!this.renderer) return;
		this.renderer.draw(this.original_board);
		for(var y = 0; y<9; y++){
			for(var x=0; x<9; x++){
				if(this.original_board[y][x] === null && this.board[y][x] !== null){
					this.renderer.drawNumber(x, y, this.board[y][x], 'blue');
				}
			}
		}
	}
	solve(draw=true, speed=-1, cb){
		return new Promise(done=>{
			cb = cb || done;
			if(!this.startTime) this.startTime = new Date().getTime();
			if(!this.checkNext(draw)){
				if(speed > -1) setTimeout(()=>this.solve(draw, speed, cb), speed);
				else this.solve(draw, speed, cb);
			}else{
				this.completeTime = new Date().getTime() - this.startTime;
				cb();
			}
		});
	}
}