<div class="formbold-main-wrapper">
  <!-- Author: FormBold Team -->
  <!-- Learn More: https://formbold.com -->
  <div class="formbold-form-wrapper">
    <img src="your-image-url-here.jpg">
    <form action="https://formbold.com/s/FORM_ID" method="POST">
      <div class="formbold-input-group">
      <label for="child_name" class="formbold-form-label">Child's Name</label>
        <input
          type="text"
          name="child_name"
          id="child_name"
          placeholder="Enter child's full name"
          class="formbold-form-input"
          required
        />
      </div>

      <div class="formbold-input-group">
        <label for="age" class="formbold-form-label">Child's Age</label>
        <input
          type="number"
          name="age"
          id="age"
          placeholder="Enter child's age"
          class="formbold-form-input"
          required
        />
      </div>

      <div class="formbold-input-group">
        <label for="parent_name" class="formbold-form-label">Parent's Name</label>
        <input
          type="text"
          name="parent_name"
          id="parent_name"
          placeholder="Enter parent's full name"
          class="formbold-form-input"
          required
        />
      </div>

      <div class="formbold-input-group">
        <label for="contact_number" class="formbold-form-label">Contact Number</label>
        <input
          type="tel"
          name="contact_number"
          id="contact_number"
          placeholder="Enter contact number"
          class="formbold-form-input"
          required
        />
      </div>

      <div class="formbold-input-group">
        <label for="email" class="formbold-form-label">Email</label>
        <input
          type="email"
          name="email"
          id="email"
          placeholder="Enter email address"
          class="formbold-form-input"
        />
      </div>

      <div class="formbold-input-group">
        <label for="class_category" class="formbold-form-label">
          Class Category
        </label>
        <select class="formbold-form-select" name="class_category" id="class_category" required>
          <option value="art">Art Class</option>
          <option value="music">Music Class</option>
          <option value="dance">Dance Class</option>
          <option value="sports">Sports Class</option>
        </select>
      </div>

      <div class="formbold-input-radio-wrapper">
        <label class="formbold-form-label">
          Preferred Session
        </label>
        <div class="formbold-radio-flex">
          <div class="formbold-radio-group">
            <label class="formbold-radio-label">
              <input
                class="formbold-input-radio"
                type="radio"
                name="preferred_session"
                value="morning"
                required
              />
              Morning
              <span class="formbold-radio-checkmark"></span>
            </label>
          </div>
          <div class="formbold-radio-group">
            <label class="formbold-radio-label">
              <input
                class="formbold-input-radio"
                type="radio"
                name="preferred_session"
                value="afternoon"
              />
              Afternoon
              <span class="formbold-radio-checkmark"></span>
            </label>
          </div>
        </div>
      </div>

      <div class="formbold-input-group">
        <label for="message" class="formbold-form-label">
          Additional Comments or Requirements
        </label>
        <textarea
          rows="6"
          name="message"
          id="message"
          placeholder="Type here..."
          class="formbold-form-input"
        ></textarea>
      </div>

      <button class="formbold-btn" type="submit">Submit</button>
    </form>
  </div>
</div>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  body {
    font-family: 'Inter', sans-serif;
  }
  .formbold-main-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
  }

  .formbold-form-wrapper {
    margin: 0 auto;
    max-width: 570px;
    width: 100%;
    background: white;
    padding: 40px;
  }

  .formbold-input-group {
    margin-bottom: 18px;
  }

  .formbold-form-select {
    width: 100%;
    padding: 12px 22px;
    border-radius: 5px;
    border: 1px solid #dde3ec;
    background: #ffffff;
    font-size: 16px;
    color: #536387;
    outline: none;
  }

  .formbold-input-radio-wrapper {
    margin-bottom: 25px;
  }
  .formbold-radio-flex {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  .formbold-radio-label {
    font-size: 14px;
    line-height: 24px;
    color: #07074d;
    position: relative;
    padding-left: 25px;
    cursor: pointer;
  }
  .formbold-input-radio {
    position: absolute;
    opacity: 0;
    cursor: pointer;
  }
  .formbold-radio-checkmark {
    position: absolute;
    top: -1px;
    left: 0;
    height: 18px;
    width: 18px;
    background-color: #ffffff;
    border: 1px solid #dde3ec;
    border-radius: 50%;
  }
  .formbold-radio-label
    .formbold-input-radio:checked
    ~ .formbold-radio-checkmark {
    background-color: #6a64f1;
  }
  .formbold-form-input {
    width: 100%;
    padding: 13px 22px;
    border-radius: 5px;
    border: 1px solid #dde3ec;
    background: #ffffff;
    font-weight: 500;
    font-size: 16px;
    color: #07074d;
    outline: none;
  }
  .formbold-btn {
    text-align: center;
    width: 100%;
    font-size: 16px;
    border-radius: 5px;
    padding: 14px 25px;
    border: none;
    font-weight: 500;
    background-color: #6a64f1;
    color: white;
    cursor: pointer;
    margin-top: 25px;
  }
</style>