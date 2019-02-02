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
			
			.clear{ clear:both; margin-bottom: 1em; }
		</style>
    </head>
    <body>
		<center>
			<h1>Sudoku Solver</h1>
			<p id='instructions'>Enter the numbers in the boxes (or <button id='rand'>Generate random board</button>)</p>
			
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
			
			<select id='mode'>
				<option value='fast'>Instantly</option>
				<option value='slow'>Animated</option>
			</select>
			<button id="solve">Solve it</button>
			
			<canvas id="canvas" style='display:none;'></canvas>
			<div id="results" style='display:none;'></div>
			<button id="reset" style='display:none;'>Reset</button>
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
				
				if(!validateBoard(board)) return alert("Not a valid puzzle. Please fix and try again.");
				
				var mode = document.getElementById('mode').value;
				
				document.getElementById('mode').style.display = 'none';
				document.querySelector(".container").style.display = 'none';
				document.getElementById('solve').style.display = 'none';
				document.getElementById('instructions').style.display = 'none';
				
				document.getElementById('canvas').style.display = null;
				document.getElementById('results').style.display = null;
				document.getElementById('reset').style.display = null;
				
				var renderer = new SudokuCanvasRenderer(document.getElementById('canvas'), 300);
				var solver = new SudokuSolver(renderer, board);
				solver.solve(mode!=='fast', mode==='fast'?0:3).then(()=>{
					if(!solver.solvable){
						solver.board = board;
						solver.draw();
						window.solver = solver;
						return alert('Puzzle not solvable.');
					}
					document.getElementById('results').innerHTML = `Moves: ${solver.permutation}, Time: ${solver.completeTime}ms`;
				});
			});
			
			document.getElementById('reset').addEventListener('click', function(){
				document.getElementById('mode').style.display = null;
				document.querySelector(".container").style.display = null;
				document.getElementById('solve').style.display = null;
				document.getElementById('instructions').style.display = null;
				
				document.getElementById('canvas').style.display = 'none';
				document.getElementById('results').style.display = 'none';
				document.getElementById('reset').style.display = 'none';
				
				document.querySelectorAll("input").forEach(input=>input.value='');
			});
			
			document.getElementById('rand').addEventListener('click', function(){
				board = randomBoard();
				document.querySelectorAll('.row-container').forEach((row, y)=>{
					row.querySelectorAll('input').forEach((input, x)=>input.value=board[y][x]);
				});
			});
			
			function randomBoard(){
				var board = newBoard();
				const rand = (min, max) => Math.floor(Math.random() * (Math.floor(max) - Math.ceil(min) + 1)) + Math.ceil(min);
				const oneThird = () => rand(1, 3)===1;
				for(let x=0; x<9; x++){
					for(let y=0; y<9; y++){
						if(!oneThird()) continue;
						let trynumber = 4;
						let valid = false;
						while(!valid && trynumber--){
							board[y][x] = rand(1,9);
							valid = validateBoard(board);
						}
						if(!valid) board[y][x] = null;
					}
				}
				return board;
			}
			
			function validateBoard(board){
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
				return valid;
			}
			
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

		</script>
    </body>
</html>
