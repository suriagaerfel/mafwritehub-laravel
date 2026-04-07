<?php //---------------------------FOR PROFILE UPDATE-------------------------------------?>
<div class="modal website-modal website-modal-wrapper" id="modal-update-profile">
    <div class="website-modal-content" style="width: 90%;">
         <div class="close-button-container">
            <span class="close-modal" id="update-profile-details-cancel-button">✖</span>
        </div>
         <textarea placeholder="Description" class="description profile-description profile-details-edit" id="profile-description-edit"></textarea>

        <div style="display: flex; gap:10px;" id="profile-details-non-description">
            <div class="profile-details-group">
                <?php if ($type=='Personal') { ?>
                <input type="text" placeholder="First Name" class="profile-details-edit" id="profile-first-name-edit">
            
                <input type="text" placeholder="Middle Name" class="profile-details-edit" id="profile-middle-name-edit">
            
                <input type="text" placeholder="Last Name" class="profile-details-edit" id="profile-last-name-edit">
                    
                <?php } ?>


                <?php if($type=='School') {?>
                <input type="text" title="Account Name" placeholder="Name" class="profile-details-edit" id="profile-account-name-edit">

                <select id="profile-school-category-edit" class="profile-details-edit">
                    <option value="" hidden>Category</option>
                    <option  value="Elementary School">Elementary School</option>
                    <option  value="Junior High School">Junior High School</option>
                    <option  value="Senior High School" >Senior High School</option>
                    <option  value="College or University" >College or University</option>
                    <option  value="Integrated School">Integrated School</option>
                </select>         
                <?php } ?>

                <input type="text" class="profile-details-edit" id="profile-username-edit" placeholder="Username">
                    
                <input type="text" placeholder='Email Address' disabled class="profile-details-edit" id="profile-email-address-edit">
        
                <input type="text" placeholder="Mobile Number" class="profile-details-edit" id="profile-mobile-number-edit">
            </div>

            <?php if ($type=='Personal') { ?>
                    <div class="profile-details-group" >  

                        <input type="date"  class="profile-details-edit" id="profile-birthdate-edit">
                    
                        <select class="profile-details-edit" id="profile-gender-edit">
                            <option value="" hidden selected>Gender</option>
                            <option  value="Male" >Male</option>
                            <option value="Female" >Female</option>
                            <option value="Other Gender">Other Gender</option>
                            <option value="No Gender" >No Gender</option>
                            <option value="Hide Gender" >Hide Gender</option>
                        </select> 
                
                            
                        <select class="profile-details-edit" id="profile-civil-status-edit">
                            <option value="" hidden selected>Civil Status</option>
                            <option  value="Single" >Single</option>
                            <option  value="Married">Married</option>
                            <option  value="Widowed">Widowed</option>
                            <option  value="Divorced">Divorced</option>
                            <option  value="Separated">Separated</option>
                            <option  value="Common-law">Common-law</option>   
                        </select>                            
                        
                        

                        <select class="profile-details-edit" id="profile-educational-attainment-edit">
                            <option value="" hidden selected>Educational Attainment</option>
                            <option  value="Elementary Undergraduate">Elementary Undergraduate</option>
                            <option  value="Elementary Graduate">Elementary Graduate</option>
                            <option  value="High School Undergraduate">High School Undergraduate</option>
                            <option  value="High School Graduate">High School Graduate</option>
                            <option  value="Associate Degree Holder">Associate Degree Holder</option>
                            <option  value="College Undergraduate">College Undergraduate</option>
                            <option  value="College Graduate" >College Graduate</option>
                            <option  value="with Master's Degree">with Master's Degree</option>
                            <option  value="with Doctorate Degree">with Doctorate Degree</option>
                        </select>
                    
                            
                        <input type="text"  placeholder="School" class="profile-details-edit" id="profile-school-edit">
                        
                            
                        <input type="text" placeholder="Occupation" class="profile-details-edit" id="profile-occupation-edit">
                        
                        
                    </div>

                <?php } ?>
            <div class="profile-details-group">
                <input type="text" value="Philippines" placeholder="Country" hidden id="profile-country">
                <?php // Path to your JSON file
                $data = json_decode(file_get_contents(asset('data/philippine_provinces_cities_municipalities_and_barangays_2019v2.json')), true);

                // Prepare regions list for initial dropdown
                $regions = [];
                foreach ($data as $regionCode => $regionData) {
                    $regions[$regionCode] = $regionData['region_name'];
                }?>

                        
                <select id="profile-region" class="profile-details-edit">
                    <option value="">Select Region</option>  
                    <?php foreach ($regions as $code => $name): ?>
                    <option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?></option>
                    <?php endforeach; ?>
                </select>
            
                <select id="profile-province-state" class="profile-details-edit">
                    <option value="" selected hidden>Select Province/State</option>
                </select>
                
                <select id="profile-city-municipality" class="profile-details-edit">
                    <option value="" selected hidden>Select City/Municipality</option>
                </select>
                
                <select id="profile-barangay" class="profile-details-edit">
                    <option value="" selected hidden>Select Barangay</option>
                </select>
                        
                <input type="text" placeholder="Street/Subd./Village" class="profile-details-edit" id="profile-street-subd-village">
                
                <div class="cancel-submit-buttons-container profile-details-edit" >
                    <button id="update-profile-details-submit-button" class="profile-details-edit link-tag-button">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  // Pass the PHP data to JavaScript
  const data = <?php echo json_encode($data) ?>

  const regionSelect = document.getElementById('profile-region');
  const provinceSelect = document.getElementById('profile-province-state');
  const municipalitySelect = document.getElementById('profile-city-municipality');
  const barangaySelect = document.getElementById('profile-barangay');
 
  regionSelect.addEventListener('change', function() {
      const regionCode = this.value;
      provinceSelect.innerHTML = '<option value=""> Select Province/State </option>';
      municipalitySelect.innerHTML = '<option value=""> Select City/Municipality </option>';
      barangaySelect.innerHTML = '<option value=""> Select Barangay</option>';
      municipalitySelect.disabled = true;
      barangaySelect.disabled = true;

      if(regionCode && data[regionCode]) {
          const provinces = data[regionCode]['province_list'];
          for(let p in provinces) {
              let option = document.createElement('option');
              option.value = p;
              option.textContent = p;
              provinceSelect.appendChild(option);
          }
          provinceSelect.disabled = false;
      } else {
          provinceSelect.disabled = true;
      }
  });

  provinceSelect.addEventListener('change', function() {
      const regionCode = regionSelect.value;
      const province = this.value;
      municipalitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
      barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
      barangaySelect.disabled = true;

      if(regionCode && province && data[regionCode]['province_list'][province]) {
          const municipalities = data[regionCode]['province_list'][province]['municipality_list'];
          for(let m in municipalities) {
              let option = document.createElement('option');
              option.value = m;
              option.textContent = m;
              municipalitySelect.appendChild(option);
          }
          municipalitySelect.disabled = false;
      } else {
          municipalitySelect.disabled = true;
      }
  });

  municipalitySelect.addEventListener('change', function() {
      const regionCode = regionSelect.value;
      const province = provinceSelect.value;
      const municipality = this.value;
      barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

      if(regionCode && province && municipality && data[regionCode]['province_list'][province]['municipality_list'][municipality]) {
          const barangays = data[regionCode]['province_list'][province]['municipality_list'][municipality]['barangay_list'];
          for(let i=0; i < barangays.length; i++) {
              let option = document.createElement('option');
              option.value = barangays[i];
              option.textContent = barangays[i];
              barangaySelect.appendChild(option);
          }
          barangaySelect.disabled = false;
      } else {
          barangaySelect.disabled = true;
      }
  });

</script>