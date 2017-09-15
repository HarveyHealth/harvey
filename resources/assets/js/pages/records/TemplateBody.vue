<template>
  <div style="height: 800px; padding: 10px; overflow-x: hidden; overflow-y: scroll;">  
                        
        <div style="float: left; width: 64%; position: relative; top: 25px;">
            <h7 name="" class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Subject</h7>
            <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
        </div>

        <div style="float: left; width: 35%; position: relative; top: 15px; left: 15px;">
            <div style="padding: 10px;">
            <h7 name="" class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Client Intake</h7>
            <div class="inline-centered" style="background-color: #f8f8f8; height: 100px; margin: 15px;">
                <button class="button" style="margin: 33px auto;">Intake Form</button>
            </div>
            </div>
        </div>

        <div style="width: 97%; position: relative; top: 15px;">
            <h7 name="" class="card-header" style="height: 20px; margin: 20px 15px; padding: 5px;">Objective</h7>
            <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
        </div>

        <div style="width: 97%; position: relative; top: 15px;">
            <h7 name="" class="card-header" style="height: 20px; margin: 20px 15px; padding: 5px;">Assessment</h7>
            <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;"/>
        </div>

        <div style="color: #EDA1A6; padding: 5px; padding-top: 10px; width: 97%; margin: 0 20px;">
            - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        </div>

        <div style="float: left; width: 64%; position: relative; top: 25px;">
            <h7 name="" class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Treatment</h7>
            <textarea class="input--textarea" placeholder="Enter your text..." style="min-height: 100px; margin: 15px;" />
        </div>

        <div style="float: left; width: 35%; position: relative; top: 15px; left: 15px;">
            <div style="padding: 10px;">
                <h7 name="" class="card-header" style="height: 20px; margin: 15px; padding: 5px;">Prescription</h7>
                <div class="inline-centered" style="background-color: #f8f8f8; height: 100px; margin: 15px;">
                    <button class="button" style="margin: 33px auto;">Prescription</button>
                </div>
            </div>
        </div>
            
        <div class="inline-centered">
            <button class="button" style="margin-top: 35px;">Save Changes</button>
        </div>
    </div>
</template>

<script>
export default {
  
}
</script>


<style>

</style>
