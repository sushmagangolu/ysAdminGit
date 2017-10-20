<?php include 'inc/header.php'; ?>
<!-- include summernote css/js-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">

		<!-- Titlebar -->
		<div id="titlebar">
			<div class="row">
				<div class="col-md-12">
					<h2>Add Listing</h2>
					<!-- Breadcrumbs -->
					<nav id="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li><a href="#">Dashboard</a></li>
							<li>Add Listing</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
		<form id="addForm" name="addForm" method='post' class="switchLabel" enctype="multipart/form-data">
		              <input type="hidden" name="action" value="add">

			<div class="card-box">
				<ul class="tabs-nav">
          <li class="active">
						<a href="#general"><i class="sl sl-icon-doc"></i> General</a>
					</li>
          <li>
						<a href="#location"><i class="sl sl-icon-location"></i> Location</a>
					</li>
          <li>
						<a href="#addmissions"><i class="fa fa-book"></i> Admissions</a>
          </li>
          <li>
						<a href="#facilities"><i class="fa fa-arrows"></i> Facilities</a>
          </li>
          <li>
						<a href="#brand"><i class="fa fa-leanpub"></i> Brand</a>
          </li>
        </ul>
				<!-- Tabs Content -->


        <div class="tabs-container">
					<!--start-->
          <div class="tab-content" id="general">
						<div class="row">

							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
                                        <label class="form-label style3"> Verified </label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" checked id="verified" name="verified" switch="none"><span class="slider round"></span></label>
	                                        <label for="verified" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
																				<div class="clear">

																				</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="collection_type" class="form-label" >School Type</label>
                                        <select class="form-control" id="collection_type" name="collection_type">
                                            <option value="1">Convent Schools</option>
                                            <option value="2">Heritage Schools</option>
                                            <option value="3">Sporting Excellence</option>
                                            <option value="4">Newly Established</option>
                                            <option value="5">Alternate Style Education</option>
                                            <option value="6">Kendriya Vidyalayas</option>
                                            <option value="7">Podar International Schools</option>
                                            <option value="8">SSRVM Schools</option>
                                            <option value="0">Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="slate_name" class="form-label">School Name </label>
                                        <input type="text" class="form-control" name="slate_name"  id="slate_name" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone Number </label>
                                        <input type="text" class="form-control" name="phone"  id="phone" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email </label>
                                        <input type="text" class="form-control" name="email"  id="email" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="website" class="form-label">Website </label>
                                        <input type="text" class="form-control" name="website"  id="website" value="" >
                                    </div>
																		<div class="form-group">
																				<label for="established" class="form-label">Established year </label>
																				<input type="text" class="form-control" name="established"  id="established" value="" >
																		</div>

							</div><!--col-md-4-->
							<div class="col-xs-12 col-sm-6 col-lg-6 col-md-6">
								<div class="form-group">
										<label class="form-label style3"> Admissions open </label>
										<div class="pull-right">
											<label class="switch"><input type="checkbox" class="no-admin" id="admissions_open" name="admissions_open" switch="none"><span class="slider round"></span></label>
											<label for="admissions_open" data-on-label="Yes" data-off-label="No" ></label>
										</div>
								</div>
								<div class="form-group">
										<label for="grade" class="form-label">Grade </label>
										<input type="text" class="form-control" name="grade"  id="grade" value="" >
								</div>
								<div class="form-group">
										<label for="medium" class="form-label">Medium </label>
										<select data-placeholder="Select Multiple Items" class="chosen-select" multiple id="medium" name="medium">
												<option value="1">English</option>
												<option value="2">TELUGU</option>
												<option value="3">URDU</option>
										</select>
								</div>
								<div class="form-group">
										<label for="board" class="form-label">Board</label>
											<select data-placeholder="Select Multiple Items" id="board" name="board" class="chosen-select" multiple>
												<option value="1">CBSE</option>
												<option value="2">State Board</option>
												<option value="3">ICSE</option>
												<option value="4">IB</option>
												<option value="5">IGCSE</option>
												<option value="6">Other</option>
										</select>
								</div>
								<div class="form-group">
										<label for="school_type" class="form-label">School Type</label>
										<select class="form-control" id="school_type" name="school_type">
												<option value="1">CO-EDUCATION</option>
												<option value="2">Boys</option>
												<option value="3">Girls</option>
										</select>
								</div>
								<div class="form-group">
										<label for="management" class="form-label">Management Type</label>
										<select class="form-control" id="management" name="management">
												<option value="1">Private</option>
												<option value="2">Government Aided</option>
												<option value="3">Unaided</option>
												<option value="4">Private Aided</option>
												<option value="5">Un-Recognised</option>
												<option value="6">Partially Aided</option>
												<option value="7">Others</option>
										</select>
								</div>
								<div class="form-group">
										<label for="description" class="form-label">About School </label>
										<div class="summernote" id="description" name="description" required></div>
								</div>

							</div>
						</div>
					</div>
					<!--end-->
					<!--start location-->
          <div class="tab-content" id="location">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
                                        <label for="state" class="control-label">State</label>
                                        <select class="form-control" id="state" name="state">
                                            <option value="0">Select State</option>
                                            <option value="1">Andaman and Nicobar Islands</option>
                                            <option value="2">Andhra Pradesh</option>
                                            <option value="3">Arunachal Pradesh</option>
                                            <option value="4">Assam</option>
                                            <option value="5">Bihar</option>
                                            <option value="6">Chandigarh</option>
                                            <option value="7">Chhattisgarh</option>
                                            <option value="8">Dadra and Nagar Haveli</option>
                                            <option value="9">Daman and Diu</option>
                                            <option value="10">Delhi</option>
                                            <option value="11">Goa</option>
                                            <option value="12">Gujarat</option>
                                            <option value="13">Haryana</option>
                                            <option value="14">Himachal Pradesh</option>
                                            <option value="15">Jammu and Kashmir</option>
                                            <option value="16">Jharkhand</option>
                                            <option value="17">Karnataka</option>
                                            <option value="18">Kerala</option>
                                            <option value="19">Lakshadweep</option>
                                            <option value="20">Madhya Pradesh</option>
                                            <option value="21">Maharashtra</option>
                                            <option value="22">Manipur</option>
                                            <option value="23">Meghalaya</option>
                                            <option value="24">Mizoram</option>
                                            <option value="25">Nagaland</option>
                                            <option value="26">Orissa</option>
                                            <option value="27">Pondicherry</option>
                                            <option value="28">Punjab</option>
                                            <option value="29">Rajasthan</option>
                                            <option value="30">Sikkim</option>
                                            <option value="31">Tamil Nadu</option>
                                            <option value="32">Tripura</option>
                                            <option value="33">Telangana</option>
                                            <option value="34">Uttar Pradesh</option>
                                            <option value="35">West Bengal</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="city" class="form-label">City </label>
                                        <input type="text" class="form-control" name="city"  id="city" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="area" class="form-label">Area </label>
                                        <input type="text" class="form-control" name="area"  id="area" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="form-label">Address </label>
                                        <textarea  class="form-control" name="address"  id="address" rows="5"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="directions" class="form-label">Directions </label>
                                        <textarea class="form-control" name="directions"  id="directions" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <a href="#" class="btn">Get Latitude and Longitude</a>
                                    </div>
                                    <div class="form-group">
                                        <label for="lat" class="form-label">Latitude </label>
                                        <input type="text" class="form-control" name="lat"  id="lat" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="lng" class="form-label">Longitude </label>
                                        <input type="text" class="form-control" name="lng"  id="lng" value="" >
                                    </div>

								</div>
							</div>
					</div>
					<!--end location-->
					<!--start addmissions-->
          <div class="tab-content" id="addmissions">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
                                        <label for="total_boys_student" class="form-label">No.of Boys</label>
                                        <input type="text" class="form-control" name="total_boys_student"  id="total_boys_student" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="total_girls_student" class="form-label">No.of Girls </label>
                                        <input type="text" class="form-control" name="total_girls_student"  id="total_girls_student" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="fee_range" class="form-label">Fee Range</label>
                                        <input type="text" class="form-control" name="fee_range"  id="fee_range" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="admission_procedure" class="form-label">Admissions Procedure</label>
                                        <textarea class="form-control" rows="5" cols="80" id="admission_procedure" name="admission_procedure"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="admission_documents" class="form-label">Required Documents </label>
                                        <textarea class="form-control" rows="5" cols="80" id="admission_documents" name="admission_documents"></textarea>
                                    </div>

								</div>
							</div>
					</div>
					<!--end addmissions-->
					<!--start facilities-->
          <div class="tab-content" id="facilities">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
                                        <label class="form-label style3"> Library </label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="library" name="library" switch="none" ><span class="slider round"></span></label>
	                                        <label for="library" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
                                    </div>
                                    <div class="form-group">
                                      <label for="books" class="form-label">Books</label>
                                      <input type="text" class="form-control" name="books" id="books" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label style3"> Smart Class Rooms </label>
                                        <div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="smart_classrooms" name="smart_classrooms" switch="none"  ><span class="slider round"></span></label>
	                                        <label for="smart_classrooms" data-on-label="Yes" data-off-label="No" ></label>
                                        </div>
																				<div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Computer Facility</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="computer_facility" name="computer_facility" switch="none"  ><span class="slider round"></span></label>
	                                        <label for="computer_facility" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
																				<div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Cafeteria</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="cafeteria" name="cafeteria" switch="none" ><span class="slider round"></span></label>
	                                        <label for="cafeteria" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
																				<div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Pre School</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="pre_school" name="pre_school" switch="none"><span class="slider round"></span></label>
	                                        <label for="pre_school" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
																				<div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">CCTV</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="cctv" name="cctv" switch="none"><span class="slider round"></span></label>
	                                        <label for="cctv" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
																				<div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Fire Escape</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="fire_escape" name="fire_escape" switch="none"><span class="slider round"></span></label>
	                                        <label for="fire_escape" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
																				<div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Transport Facility</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="transport" name="transport" switch="none"><span class="slider round"></span></label>
	                                        <label for="transport" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
																				<div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="transport_ownership" class="form-label">Transport Ownership</label>
                                        <select class="form-control" id="transport_ownership" name="transport_ownership">
                                            <option value="1" >Owned</option>
                                            <option value="2">Contracted</option>
                                            <option value="3">Owned & Contracted</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_hostel_capacity" class="form-label">Hostel Capacity</label>
                                        <input type="text" class="form-control" name="total_hostel_capacity"  id="total_hostel_capacity" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Laboratory</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="laboratory" name="laboratory" switch="none"><span class="slider round"></span></label>
	                                        <label for="laboratory" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="labs" class="form-label">Labs</label>
                                        <textarea class="form-control" name="labs"  id="labs" rows="3" ></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Medical</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="medical" name="medical" switch="none"   ><span class="slider round"></span></label>
	                                        <label for="medical" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
                                    </div>
                                     <div class="form-group">
                                        <label for="medical_facilites" class="form-label">Medical Facility</label>
                                        <textarea class="form-control" name="medical_facilites"  id="medical_facilites" rows="3" ></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="school_name" class="style3 form-label">Playground</label>
																				<div class="pull-right">
																					<label class="switch"><input type="checkbox" class="no-admin" id="playground" name="playground" switch="none"><span class="slider round"></span></label>
	                                        <label for="playground" data-on-label="Yes" data-off-label="No" ></label>
																				</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="playground_purpose" class="form-label">Playground Purpose</label>
                                        <textarea class="form-control" name="playground_purpose"  id="playground_purpose" rows="3" ></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="instructors_coaches" class="form-label">Instructors/Coaches Available for</label>
                                        <textarea class="form-control" name="instructors_coaches"  id="instructors_coaches" rows="3" ></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="sports_facilities" class="form-label">Sports</label>
                                        <textarea class="form-control" name="sports_facilities"  id="sports_facilities" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="extra_curricular_activities" class="form-label">Extra Curricular Activities</label>
                                        <textarea class="form-control" name="extra_curricular_activities"  id="extra_curricular_activities" rows="3"></textarea>
                                    </div>

							</div>
						</div>
					</div>
					<!--end facilities-->
					<!--start brand-->
          <div class="tab-content" id="brand">
						<div class="col-md-4">
							<div class="form-group">
                                        <label class="form-label"> Logo </label>
                                        <input id="" name="logo" class="input-file" type="file">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"> Profile Pic </label>
                                        <input id="profile_pic" name="profile_pic" class="input-file" type="file">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"> Cover Pic </label>
                                        <input id="cover_pic" name="cover_pic" class="input-file" type="file">
                                    </div>

						</div>
					</div>
					<!--end brand-->

				</div><!--tab container-->

				<div class="row">
					<div class="pull-right">
						<button type="button" id="editRole" class="button" onclick="school.save();">Save Settings</button>
					</div>
				</div>
			</div><!--card-box-->
		</form>

	<?php include 'inc/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>

	 <script type="text/javascript">
	 $('.summernote').summernote({
		  height: 350, // set editor height
		  minHeight: null, // set minimum height of editor
		  maxHeight: null, // set maximum height of editor
		  focus: false // set focus to editable area after initializing summernote
   });
	 </script>
 	 <script src="module/schools.js"></script>
