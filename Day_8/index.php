<?php include 'header.php'; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title mb-3">
            <i class="fa-solid fa-user-plus"></i> Student Registration Form
        </h3>

        <!-- 
            method="POST" -> sends form data in the request body (not the URL),
            action="process.php" -> tells the browser which file should handle the submitted data
        -->
        <form action="process.php" method="POST">

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
            </div>

            <!-- CGPA -->
            <div class="mb-3">
                <label for="cgpa" class="form-label">CGPA</label>
                <input type="number" step="0.01" min="0" max="10" class="form-control" id="cgpa" name="cgpa" placeholder="e.g. 8.5">
            </div>

            <!-- Branch -->
            <div class="mb-3">
                <label for="branch" class="form-label">Branch</label>
                <input type="text" class="form-control" id="branch" name="branch" placeholder="e.g. Computer Science">
            </div>

            <!-- College -->
            <div class="mb-3">
                <label for="college" class="form-label">College</label>
                <input type="text" class="form-control" id="college" name="college" placeholder="Enter your college name">
            </div>

            <!-- Gender - radio buttons -->
            <div class="mb-3">
                <label class="form-label d-block">Gender</label>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male">
                    <label class="form-check-label" for="genderMale">Male</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female">
                    <label class="form-check-label" for="genderFemale">Female</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Other">
                    <label class="form-check-label" for="genderOther">Other</label>
                </div>
            </div>

            <!-- Course - dropdown -->
            <div class="mb-3">
                <label for="course" class="form-label">Course</label>
                <select class="form-select" id="course" name="course">
                    <option value="" selected disabled>Choose a course</option>
                    <option value="B.Tech">B.Tech</option>
                    <option value="B.Sc">B.Sc</option>
                    <option value="BCA">BCA</option>
                    <option value="MCA">MCA</option>
                    <option value="M.Tech">M.Tech</option>
                </select>
            </div>

            <!-- Address - textarea -->
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address"></textarea>
            </div>

            <!-- Photo upload - BONUS: this is a UI placeholder only.
                 No server-side upload handling is done for it in process.php. -->
            <div class="mb-3">
                <label for="photo" class="form-label">Profile Photo (optional)</label>
                <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-paper-plane"></i> Submit Registration
            </button>

        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
