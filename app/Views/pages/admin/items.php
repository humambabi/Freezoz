<div class="main-container" style="position: relative;">
	<?php insert_hspace("4.3rem"); ?>

	<?php insert_hspace("5rem"); ?>
	<h5><strong>All items in the system:</strong></h5>

	<!-- Items container -->
	<div id="itemtable-container"></div>

	<!-- Control bar -->
	<div style="display: flex; flex-direction: row; justify-content: space-between;">
		<div>
			<button id="itemDelete" class="btn btn-danger" disabled><i class="fas fa-trash-alt"></i> Delete</button>
			<button id="itemEdit" class="btn btn-primary" disabled><i class="fas fa-edit"></i> Edit</button>
		</div>
		<div>
			<button id="itemAdd" class="btn btn-success" type="button" data-toggle="modal" data-target="#modalItemAdd"><i class="fas fa-plus-circle"></i> Add</button>
		</div>
	</div>
	
	<!-- Modal: Add Item -->
	<div class="modal fade" id="modalItemAdd" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticModalItemAdd" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticModalItemAdd">Add a New Item</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form id='formItemAdd'>
						<!-- Date of addition -->
						<div class="form-group">
							<label class="font-weight-bold" for="dateAddition">Date of addition</label>
							<div class="row">
								<div class="col" style="display: flex; flex-direction: column; justify-content: center;">
									<input type="date" class="form-control" id="dateAddition" required disabled>
								</div>
								<div class="col" style="display: flex; flex-direction: column; justify-content: center;">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" id="chkToday" checked>
										<label class="form-check-label" for="chkToday">Today</label>
									</div>
								</div>
							</div>
						</div>

						<!-- Title -->
						<div class="form-group">
							<label for="textItemTitle" class="font-weight-bold">Title</label>
							<input type="text" maxlength="<?= ITEMS_TITLE_MAXLENGTH ?>" class="form-control" id="textItemTitle" placeholder="Type item's title, for home & item pages." required>
						</div>

						<!-- Description -->
						<div class="form-group">
							<label for="textItemDesc" class="font-weight-bold">Description</label>
							<small id="desc-invalidlabel" class="form-text text-danger d-none">You should write something here!</small>
							<textarea class="form-control" id="textItemDesc" required></textarea>
						</div>

						<!-- Item's file -->
						<div class="form-group">
							<div class="row">
								<div class="col" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Item's compressed file</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="fileItemFile" accept="<?= $ItemFileInputAccept ?>">
										<label class="custom-file-label" for="fileItemFile">Choose file</label>
									</div>
									<small class="form-text text-muted">The item's comprssed file. (<?= $ItemFileDescAccept ?> file types)</small>
								</div>
							</div>
						</div>

						<!-- Tags -->
						<div class="form-group">
							<label for="textItemTags" class="font-weight-bold">Tags</label>
							<div class="row clearfix">
								<div class="col-md-10">
									<input type="text" class="form-control" id="textItemTags" placeholder="Type one tag (no spaces or special characters)" required>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-success float-right" id="btnTagAdd">Add Tag</button>
								</div>
							</div>
							<div class="row" style="margin-top: .25rem;">
								<div class="col" id="curTags">
									<small>Current tags:</small>
									<span class="badge badge-secondary" type="none">None</span>
								</div>
							</div>
						</div>

						<br/><hr/><br/>

						<!-- Image Preview (Small) -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-7" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Thumbnail image</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="filePrevImgSmall" accept="<?= $ItemImageAccept ?>">
										<label class="custom-file-label" for="filePrevImgSmall">Choose file</label>
									</div>
									<small class="form-text text-muted">A small image for the home page (<?= ITEMS_IMAGE_SMALL_DIMENSIONS['width'] ?>x<?= ITEMS_IMAGE_SMALL_DIMENSIONS['height'] ?> px - <?= $ItemImageDescAccept ?> file types)</small>
								</div>
								<div class="col-md-5">
									<img id="imgPrevImgSmall" src="<?= base_url() ?>/img/image-small-none.jpg" class="img-thumbnail" alt="Preview Small Image">
									<div class="imgdims"></div>
								</div>
							</div>
						</div>

						<!-- Image Preview (Full) -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-7" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Full-sized image</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="filePrevImgFull" accept="<?= $ItemImageAccept ?>">
										<label class="custom-file-label" for="filePrevImgFull">Choose file</label>
									</div>
									<small class="form-text text-muted">A full-sized image for the item's page (<?= ITEMS_IMAGE_FULL_DIMENSIONS['width'] ?>x<?= ITEMS_IMAGE_FULL_DIMENSIONS['height'] ?> px - <?= $ItemImageDescAccept ?> file types)</small>
								</div>
								<div class="col-md-5">
									<img id="imgPrevImgFull" src="<?= base_url() ?>/img/image-small-none.jpg" class="img-thumbnail" alt="Preview Full Image">
									<div class="imgdims"></div>
								</div>
							</div>
						</div>

						<!-- Video Preview (Small) -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-7" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Small preview video</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="filePrevVidSmall" accept="<?= $ItemVideoAccept ?>">
										<label class="custom-file-label" for="filePrevVidSmall">Choose file</label>
									</div>
									<small class="form-text text-muted">A small-sized preview video for the home page (<?= ITEMS_VIDEO_SMALL_DIMENSIONS['width'] ?>x<?= ITEMS_VIDEO_SMALL_DIMENSIONS['height'] ?> px - <?= $ItemVideoDescAccept ?> file type)</small>
								</div>
								<div class="col-md-5">
									<video id='vidPrevVidSmall' class='img-thumbnail' width='100%' height='auto' playsinline='playsinline' muted='muted' loop='loop' poster='<?= base_url() ?>/img/video-small-none.jpg' preload='auto'>
										<source>
									</video>
									<div class="viddims"></div>
								</div>
							</div>
						</div>

						<!-- Item's Video (Full) -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-7" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Full-sized video</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="filePrevVidFull" accept="<?= $ItemVideoAccept ?>">
										<label class="custom-file-label" for="filePrevVidFull">Choose file</label>
									</div>
									<small class="form-text text-muted">A full-sized video for the item's page (<?= ITEMS_VIDEO_FULL_DIMENSIONS['width'] ?>x<?= ITEMS_VIDEO_FULL_DIMENSIONS['height'] ?> px - <?= $ItemVideoDescAccept ?> file type)</small>
								</div>
								<div class="col-md-5">
									<video id='vidPrevVidFull' class='img-thumbnail' width='100%' height='auto' playsinline='playsinline' muted='muted' loop='loop' poster='<?= base_url() ?>/img/video-small-none.jpg' preload='auto'>
										<source>
									</video>
									<div class="viddims"></div>
								</div>
							</div>
						</div>

						<!-- Additional images -->
						<div class="form-group">
							<div class="row">
								<div class="col" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Additional images (Optional)</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="fileAddImages" multiple accept="<?= $ItemImageAccept ?>">
										<label class="custom-file-label" for="fileAddImages">Choose file</label>
									</div>
									<small class="form-text text-muted">Select one or more images (any size) for the item's page. (<?= $ItemImageDescAccept ?> file types)</small>
								</div>
							</div>
						</div>

						<!-- Additional videos -->
						<div class="form-group">
							<div class="row">
								<div class="col" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Additional videos (Optional)</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="fileAddVideos" multiple accept="<?= $ItemVideoAccept ?>">
										<label class="custom-file-label" for="fileAddVideos">Choose file</label>
									</div>
									<small class="form-text text-muted">Select one or more videos (any size) for the item's page. (<?= $ItemVideoDescAccept ?> file type)</small>
								</div>
							</div>
						</div>

						<!-- Additional audio -->
						<div class="form-group">
							<div class="row">
								<div class="col" style="display: flex; flex-direction: column; justify-content: center;">
									<label class="font-weight-bold">Additional audio clips (Optional)</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="fileAddAudios" multiple accept="<?= $ItemAudioAccept ?>">
										<label class="custom-file-label" for="fileAddAudios">Choose file</label>
									</div>
									<small class="form-text text-muted">Select one or more additional audio clips for the item's page. (<?= $ItemAudioDescAccept ?> file type)</small>
								</div>
							</div>
						</div>

						<br/><hr/><br/>

						<!-- Price -->
						<div class="form-group">
							<label for="textItemPrice" class="font-weight-bold">Price</label>
							<input type="number" class="form-control" id="textItemPrice" placeholder="Type item's price, in $ US dollars" required>
							<small class="form-text text-muted">Set the price to zero &quot;0&quot; to indicate a free template</small>
						</div>

						<!-- License -->
						<div class="form-group">
							<label for="selectLicense" class="font-weight-bold">License</label>
							<select class="custom-select" id="selectLicense">
								<option value='0' selected>Select a license...</option>
								<?php
								for ($i = 0; $i < count(ITEMS_LICENSES); $i++) {
									echo "<option value='" . ($i + 1) . "'>" . ITEMS_LICENSES[$i]['name'] . " (" . ITEMS_LICENSES[$i]['description'] . ")</option>";
								}
								?>
							</select>
						</div>
					</form> <!-- /formItemAdd -->

					<form id='formItemUpload' class='d-none'>
						<!-- Status text -->
						<div class="form-group">
							<div class="row">
								<div class="col-md-2">
									<div class="img-thumbnail" style="font-size:3rem;width:5rem;height:5rem;display:flex;justify-content:center;align-items:center;">
										<i class="fas fa-upload"></i>
									</div>
								</div>
								<div class="col-md-10" style="display: flex; flex-direction: column; justify-content: center;">
									<h6 id='labelItemUpload'>Uploading files...</h6>
								</div>
							</div>
						</div>

						<!-- Progress bar -->
						<div class="progress">
							<div id='progressItemUpload' class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</form> <!-- /formItemUpload -->
				</div> <!-- /modal-body -->

				<div class="modal-footer" id='formItemNewFooter'>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-success" id="btnSave">&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;</button>
				</div>
			</div> <!-- /modal-contents -->
		</div> <!-- /modal-dialog -->
	</div> <!-- /modal -->

	<div style="color:rgba(0,0,0,.01);">.</div>
</div>