
"use strict";

class SudokuCanvasRenderer{
	constructor(canvas, width){
		this.canvas = canvas;
		this.width = width;
		canvas.width = width;
		canvas.height = width;
		this.ctx = canvas.getContext('2d');
		this.margin = 5;
		
	}
	draw(cells){
		this.ctx.clearRect(0, 0, this.width, this.width);
		this.ctx.lineWidth = 2;
		this.ctx.strokeStyle = 'black';
		var boardsize = this.width-(this.margin*2);
		this.ctx.strokeRect(this.margin, this.margin, boardsize, boardsize);
		for(var i=boardsize/3; i<boardsize; i+=(boardsize/3)){
			this.drawLine(this.margin, this.margin+i, this.margin+boardsize, this.margin+i, 2);
			this.drawLine(this.margin+i, this.margin, this.margin+i, this.margin+boardsize, 2);
		}
		for(var i=boardsize/9; i<boardsize; i+=(boardsize/9)){
			this.drawLine(this.margin, this.margin+i, this.margin+boardsize, this.margin+i, 1);
			this.drawLine(this.margin+i, this.margin, this.margin+i, this.margin+boardsize, 1);
		}
		cells.forEach((row, y)=>{
			row.forEach((cell, x)=>{
				if(cell !== null) this.drawNumber(x, y, cell);
			});
		});
	}
	drawNumber(gx, gy, char, color='black'){
		this.ctx.fillStyle = color;
		var cellwidth = (this.width-(this.margin*2))/9;
		var cellposition_x = (gx*cellwidth)+this.margin;
		var cellposition_y = (gy*cellwidth)+this.margin;
		var textwidth = cellwidth-(this.margin*2);
		this.ctx.font = textwidth+'px courier';
		var x = cellposition_x+(cellwidth/2);
		var y = cellposition_y+(cellwidth/2);
		this.ctx.textAlign="center"; 
		this.ctx.textBaseline = "middle";
		this.ctx.fillText(char, x, y);
	}
	drawLine(sx, sy, ex, ey, lw){
		this.ctx.lineWidth = lw;
		this.ctx.beginPath();
		this.ctx.moveTo(sx, sy);
		this.ctx.lineTo(ex, ey);
		this.ctx.stroke(); 
	}
}