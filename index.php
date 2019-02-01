<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>sudoku</title>
		<style>
			.container{
				width: 270px;
			}
			
			.row-container{
				float:left;
				width: 270px;
			}
			
			.row-container input {
				color: darkgreen;
				font-size: 16px;
				font-weight: bold;
				text-align: center;
				width: 30px;
				height: 30px;
				box-sizing: border-box;
				border: 1px solid gray;
				color:gray;
			}
			
			.row-container input:nth-child(3n) {
				border-right: 2px solid black;
			}
			
			.row-container:nth-child(3n) input{
				border-bottom: 2px solid black;
			}
			
			.row-container input:first-child{
				border-left: 2px solid black;
			}
			
			.row-container:first-child input{
				border-top: 2px solid black;
			}
			
			#canvas, #results{
				display:none;
			}
			
			.clear{ clear:both; margin-bottom: 1em; }
		</style>
    </head>
    <body>
		<center>
			<h1>Sudoku Solver</h1>
			<p>Enter the numbers in the boxes</p>
			
			<div class="container">
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
				<div class="row-container">
					<input><input><input><input><input><input><input><input><input>
				</div>
			</div>

			<div class="clear"></div>
			
			<button id="solve">Solve it</button>
			
			<canvas id="canvas"></canvas>
			<div id="results"></div>
		</center>
		
		<script src="renderer.js"></script>
		<script src="solver.js"></script>
		
		<script>
			var board = newBoard();
			document.addEventListener('keyup', function(e){
				if(e.target.tagName === "INPUT" && e.target.parentElement && e.target.parentElement.classList.contains('row-container')){
					e.target.value = e.target.value.replace(/[^\d]/g, '');
					var val = e.target.value ? parseInt(e.target.value) : '';
					if(val > 9) e.target.value = e.target.value.substr(-1);
					document.querySelectorAll('.row-container').forEach((row, y)=>{
						row.querySelectorAll('input').forEach((cell, x)=>{
							val = cell.value ? parseInt(cell.value) : null;
							board[y][x] = val;
						});
					});
				}
			});
			
			document.getElementById('solve').addEventListener('click', function(){
				var solver = new SudokuSolver(false, board);
				var valid = true;
				for(let y=0; y<board.length; y++){
					if(!solver.validateRow(y)){
						valid = false;
						break;
					}
				}
				for(let x=0; valid&&x<board[0].length; x++){
					if(!solver.validateCol(x)){
						valid = false;
						break;
					}
				}
				for(let x=0; valid&&x<3; x++){
					for(let y=0; valid&&y<3; y++){
						if(!solver.validateSubgrid(x*3, y*3)){
							valid = false;
							break;
						}
					}
				}
				if(!valid) return alert("Not a valid puzzle. Please fix and try again.");
			});
			
			function newBoard(){
				return [
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null],
					[null, null, null, null, null, null, null, null, null]
				];
			}
			
			
//			var board = [
//				[null, 1, null, null, null, 2, 8, null, 6],
//				[null, null, 3, null, null, null,9, null, null],
//				[null, null, 8, 7, 6, null, null, null, 1],
//				[1, null, null, null, 8, 6, 5, null, 7],
//				[null, 4, null, 5, null, 9, null, 8, null],
//				[8, null, 2, 1,7, null, null, null, 3],
//				[3, null, null, null, 1, 8, 2, null, null],
//				[null, null, 1, null, null, null, 3, null, null],
//				[5, null, 6, 4, null, null, null, 1, null]
//			];
//			var renderer = new SudokuCanvasRenderer(document.getElementById('canvas'), 300);
//			var solver = new SudokuSolver(renderer, board);
//			solver.solve(true, 2).then(()=>{
//				console.log("done");
//				document.getElementById('results').innerHTML = `Moves: ${solver.permutation}, Time: ${solver.completeTime}ms`;
//			});
		</script>
    </body>
</html>
