let activityCounter = 2;

function addActivityField() {
    const container = document.getElementById("activity-items-container");
    const newActivity = document.createElement("div");
    newActivity.className = "activity-item";
    newActivity.innerHTML = `
                <div class="row align-items-start">
                    <div class="col-md-9">
                        <textarea class="form-control activity-textarea" name="activities[${activityCounter}][description]" placeholder="Uraian kegiatan harian anda" rows="2"></textarea>
                    </div>
                    <div class="col-md-3">
                        <div class="priority-dropdown-container">
                            <select class="form-control priority-dropdown" name="activities[${activityCounter}][priority]">
                                <option value="">Pilih Prioritas</option>
                                <option value="urgent" class="priority-option-urgent">Urgent</option>
                                <option value="high" class="priority-option-high">High</option>
                                <option value="normal" class="priority-option-normal">Normal</option>
                                <option value="low" class="priority-option-low">Low</option>
                                <option value="clear" class="priority-option-clear">Clear</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
    container.appendChild(newActivity);
    activityCounter++;
}

document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split("T")[0];
    const dateInput = document.getElementById("activity-date");
    if (dateInput && !dateInput.value) {
        dateInput.value = today;
    }
});
