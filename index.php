<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>sudoku</title>
    </head>
    <body>
		<canvas id="canvas" style="background: grey;"></canvas>
		<script src="renderer.js"></script>
		<script src="solver.js"></script>
		
		<script>
			var board = [
				[null, 1, null, null, null, 2, 8, null, 6],
				[null, null, 3, null, null, null,9, null, null],
				[null, null, 8, 7, 6, null, null, null, 1],
				[1, null, null, null, 8, 6, 5, null, 7],
				[null, 4, null, 5, null, 9, null, 8, null],
				[8, null, 2, 1,7, null, null, null, 3],
				[3, null, null, null, 1, 8, 2, null, null],
				[null, null, 1, null, null, null, 3, null, null],
				[5, null, 6, 4, null, null, null, 1, null]
			];
			var renderer = new SudokuCanvasRenderer(document.getElementById('canvas'), 300);
			var solver = new SudokuSolver(renderer, board);
		</script>
    </body>
</html>
