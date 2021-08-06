<h1>Manage Users</h1>

<div class="mt-3">
	Showing all "<b><?= $this->Paginator->params()['count'] ?></b>" users
</div>

<div class="mt-3">
	<?php
	if (!empty($users)) {
	?>
		<div class="table-responsive">
			<table class="table text-center">
				<thead>
				<tr>
					<th>User Id</th>
					<th>Mobile</th>
					<th>User Type</th>
					<th>Created On</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($users as $row) {
						$i++;
						?>
						<tr>
							<?php /* <td><a href="/admin/users/details/<?= base64_encode($row['User']['id'])?>"><?= $row['User']['id'] ?></a></td> */ ?>
							<td><?= $row['User']['id'] ?></td>
							<td><?= $row['User']['mobile'] ?></td>
							<td><?= $row['User']['type'] ?></td>
							<td><?= date('d/m/Y', strtotime($row['User']['created'])) ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<br>
		<?php
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page ' . $this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		// Shows the next and previous links
		echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
		echo '&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();

		echo '&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	} else {
		?>
		No users found.
		<?php
	}
	?>
</div>

