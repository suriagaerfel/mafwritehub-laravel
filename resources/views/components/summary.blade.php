<div id="sm-records-container">
    <div style="display: flex; gap:10px; width:100%;" id="sm-records-filter">
        <select id="sm-record-category" class="record-input">
            <option value="" selected hidden>Select Record Category</option>
            <?php if ($websiteManagerSuperManagerRegistration || $websiteManagerRegistrationManagerRegistration) {?>
            <option value="General Registration">General Registration</option>
            <option value="Other Registration">Other Registration</option>
            <?php } ?>

            <?php if ($websiteManagerSuperManagerRegistration || $websiteManagerSubscriptionManagerRegistration) {?>
            <option value="Subscription">Subscription</option>
            <?php } ?>
            <?php if ($websiteManagerSuperManagerRegistration || $websiteManagerPromotionManagerRegistration) {?>
            <option value="Promotion">Promotion</option>
            <?php } ?>
            <?php if ($websiteManagerSuperManagerRegistration || $websiteManagerMessageManagerRegistration) {?>
             <option value="Logs">Logs</option>
            <?php }?>
            <option value="">Deselected</option>
        </select>

        <?php if ($websiteManagerSuperManagerRegistration || $websiteManagerRegistrationManagerRegistration) {?>
        <select id="sm-record-sub-category-general-registration" class="sub-category record-input" style="display: none;">
            <option value="" selected hidden>Select General Registration</option>
            <option value="Personal">Personal</option>
            <option value="School">School</option>
            <option value="">All</option>
        </select>

        <select id="sm-record-sub-category-other-registration" class="sub-category record-input"  style="display: none;">
            <option value="" selected hidden>Select Other Registration</option>
            <option value="Teacher">Teacher</option>
            <option value="Writer">Writer</option>
            <option value="Editor">Editor</option>
            <option value="Researches">Researches</option>
            <option value="Developer">Developer</option>
            <option value="">All</option>
        </select>
        <?php } ?>
            <?php if ($websiteManagerSuperManagerRegistration || $websiteManagerSubscriptionManagerRegistration) {?>
        <select id="sm-record-sub-category-subscription" class="sub-category record-input"  style="display: none;">
            <option value="" selected hidden>Select Subscription</option>
             <option value="Tools">Tools</option>
            <option value="Seller">Seller</option>
            <option value="">All</option>
        </select>
        <?php } ?>

            <?php if ($websiteManagerSuperManagerRegistration || $websiteManagerPromotionManagerRegistration) {?>
         <select id="sm-record-sub-category-promotion" class="sub-category record-input"  style="display: none;">
            <option value="" selected hidden>Select Promotion</option>
             <option value="Services">Services</option>
            <option value="Products">Products</option>
            <option value="">All</option>
        </select>
        <?php } ?>


    
        <select id="sm-record-status-general-registration" class="record-input status"  style="display: none;">
            <option value="" selected hidden>Select Status</option>
            <option value="Verified" >Verified</option>
            <option value="Unverified">Unverified</option>
            <option value="Revoked">Revoked</option>
            <option value="Kept">Kept</option>
            <option value="">All</option>
        </select>


        <select id="sm-record-status-other-registration" class="record-input status"  style="display: none;">
            <option value="" selected hidden>Select Status</option>
            <option value="Pending" >Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Revoked">Revoked</option>
            <option value="Kept">Kept</option>
            <option value="">All</option>
        </select>

        <select id="sm-record-status-subscription" class="record-input status"  style="display: none;">
            <option value="" selected hidden>Select Status</option>
            <option value="Pending" >Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Revoked">Revoked</option>
            <option value="Kept">Kept</option>
            <option value="">All</option>
        </select>

        <select id="sm-record-status-promotion" class="record-input status"  style="display: none;">
            <option value="" selected hidden>Select Status</option>
            <option value="Draft">Draft</option>
            <option value="Published">Published</option>
            <option value="Unpublished">Unpublished</option>
            <option value="">All</option>
        </select>

         <select id="sm-record-status-messages" class="record-input status"  style="display: none;">
            <option value="" selected hidden>Select Status</option>
            <option value="Read">Read</option>
            <option value="Unread">Unread</option>
            <option value="">All</option>
        </select>

        <input type="date" id="sm-record-date" class="record-input"  style="display: none;">
        <input type="search" placeholder="Search records..." id="sm-record-search" class="record-input"  style="display: none;">
    </div>
    <br>
    <div id="sm-records" style="width: 100%; display:flex;flex-direction:column;">
        
    </div>


</div>