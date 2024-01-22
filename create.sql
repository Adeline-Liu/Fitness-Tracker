DROP TABLE Goal;
DROP TABLE MoodTracking;
DROP TABLE SleepTracking;
DROP TABLE PlanContainsExercise;
DROP TABLE Equipment;
DROP TABLE DietPlanInFitnessPlan;
DROP TABLE FitnessPlan;
DROP TABLE Meal;
DROP TABLE "User";
DROP TABLE Trainer;
DROP TABLE Exercise;
DROP TABLE DietPlan;


CREATE TABLE Trainer (
    ID INTEGER PRIMARY KEY,
    Specialty VARCHAR2(256),
    YearsOfExperience INTEGER,
    Name VARCHAR2(256),
    Password VARCHAR2(256),
    EmailAddress VARCHAR2(256),
    UNIQUE (EmailAddress)
);

CREATE TABLE "User" (
    ID INTEGER PRIMARY KEY,
    Height INTEGER,
    Weight INTEGER,
    DateOfBirth DATE,
    Name VARCHAR2(256),
    Password VARCHAR2(256),
    EmailAddress VARCHAR2(256),
    TrainerID INTEGER,
    UNIQUE (EmailAddress),
    FOREIGN KEY (TrainerID) REFERENCES Trainer(ID)
);

CREATE TABLE Goal (
    GoalID INTEGER PRIMARY KEY,
    Description VARCHAR2(500),
    TargetDate DATE,
    Status VARCHAR2(500),
    UserID INTEGER not null,
    FOREIGN KEY (UserID) REFERENCES "User"(ID) on delete cascade
);


CREATE TABLE SleepTracking (
    "Date" DATE PRIMARY KEY,
    QualityDescription VARCHAR2(500),
    HoursOfSlept INTEGER,
    UserID INTEGER not null,
    FOREIGN KEY (UserID) REFERENCES "User"(ID) on delete cascade
);


CREATE TABLE MoodTracking (
    MoodID INTEGER PRIMARY KEY,
    MoodDescription VARCHAR2(500),
    "Date" DATE NOT NULL UNIQUE,
    FOREIGN KEY ("Date") REFERENCES SleepTracking("Date") on delete cascade
);


CREATE TABLE FitnessPlan (
    PlanID INTEGER PRIMARY KEY,
    StartDate DATE,
    EndDate DATE,
    GoalCalories INTEGER,
    ActualCalories INTEGER,
    UserID INTEGER not null,
    FOREIGN KEY (UserID) REFERENCES "User"(ID) on delete cascade
);


CREATE TABLE Exercise (
    ExerciseName VARCHAR2(256) PRIMARY KEY,
    Duration INTEGER,
    CaloriesBurned INTEGER
);

CREATE TABLE PlanContainsExercise (
    PlanID INTEGER, 
    ExerciseName VARCHAR2(256),
    PRIMARY KEY (PlanID, ExerciseName),
    FOREIGN KEY (PlanID) REFERENCES FitnessPlan(PlanID),
    FOREIGN KEY (ExerciseName) REFERENCES Exercise(ExerciseName) on delete set null
);

CREATE TABLE Equipment (
    EquipmentName VARCHAR2(256) PRIMARY KEY,
    BodyPartsExercised VARCHAR2(256),
    ExerciseName VARCHAR2(256) not null,
    FOREIGN KEY (ExerciseName) REFERENCES Exercise(ExerciseName) on delete cascade
);

CREATE TABLE DietPlan (
    DietID INTEGER PRIMARY KEY,
    DietName VARCHAR2(256)
);

CREATE TABLE DietPlanInFitnessPlan (
    DietID INTEGER,
    PlanID INTEGER,
    PRIMARY KEY (DietID, PlanID),
    FOREIGN KEY (DietID) REFERENCES DietPlan(DietID),
    FOREIGN KEY (PlanID) REFERENCES FitnessPlan(PlanID)
);

CREATE TABLE Meal (
    MealID INTEGER PRIMARY KEY,
    NutritionalValue VARCHAR2(500),
    Calories INTEGER,
    MealType VARCHAR2(256),
    MealDescription VARCHAR2(500),
    DietID INTEGER,
    FOREIGN KEY (DietID) REFERENCES DietPlan(DietID)
);

INSERT INTO Trainer (ID, Specialty, YearsOfExperience, Name, Password, EmailAddress) VALUES (1, 'HIIT Trainer', 5, 'Emily Jones', 'trainer1', 'trainerA@example.com');

INSERT INTO Trainer (ID, Specialty, YearsOfExperience, Name, Password, EmailAddress) VALUES (2, 'Yoga Instructor', 4, 'Samantha Patel', 'trainer2', 'trainerB@example.com');

INSERT INTO Trainer (ID, Specialty, YearsOfExperience, Name, Password, EmailAddress) VALUES (3, 'Nutrition Consultant', 6, 'David Nguyen', 'trainer3', 'trainerC@example.com');

INSERT INTO Trainer (ID, Specialty, YearsOfExperience, Name, Password, EmailAddress) VALUES (4, 'Basketball Coach', 3, 'Marcus Johnson', 'trainer4', 'trainerD@example.com');

INSERT INTO Trainer (ID, Specialty, YearsOfExperience, Name, Password, EmailAddress) VALUES (5, 'Rehab Therapist', 7, 'Isabella Martinez', 'trainer5', 'trainerE@example.com');


INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Running', 30, 300);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Cycling', 40, 350);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Swimming', 60, 500);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Yoga', 45, 150);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Weightlifting', 50, 450);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Hiking', 120, 600);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Rowing', 30, 250);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Dancing', 60, 400);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Pilates', 45, 180);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Kickboxing', 40, 350);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Skating', 50, 330);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Rock Climbing', 30, 450);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Zumba', 60, 500);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('CrossFit', 45, 400);

INSERT INTO Exercise (ExerciseName, Duration, CaloriesBurned) VALUES ('Tai Chi', 50, 200);


INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Treadmill', 'Legs', 'Running');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Cycle', 'Legs', 'Cycling');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Swimming Goggles', 'Full Body', 'Swimming');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Yoga Mat', 'Full Body', 'Yoga');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Dumbbells', 'Arms', 'Weightlifting');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Rowing Machine', 'Arms, Legs, Back', 'Rowing');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Dance Shoes', 'Full Body', 'Dancing');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Pilates Mat', 'Core, Full Body', 'Pilates');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Punching Bag', 'Arms, Legs, Cardio', 'Kickboxing');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Skates', 'Legs, Core', 'Skating');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Climbing Gear', 'Arms, Legs, Core', 'Rock Climbing');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Audio System', 'Full Body', 'Zumba');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('CrossFit Rig', 'Full Body', 'CrossFit');

INSERT INTO Equipment (EquipmentName, BodyPartsExercised, ExerciseName) VALUES ('Tai Chi Shoes', 'Full Body', 'Tai Chi');


INSERT INTO DietPlan (DietID, DietName) VALUES (1, 'Keto Diet');

INSERT INTO DietPlan (DietID, DietName) VALUES (2, 'Vegan Diet');

INSERT INTO DietPlan (DietID, DietName) VALUES (3, 'Mediterranean Diet');

INSERT INTO DietPlan (DietID, DietName) VALUES (4, 'Paleo Diet');

INSERT INTO DietPlan (DietID, DietName) VALUES (5, 'Low-Carb Diet');


INSERT INTO "User" (ID, Height, Weight, DateOfBirth, Name, Password, EmailAddress, TrainerID) 
VALUES (1, 180, 75, TO_DATE('1990-01-01', 'yyyy-mm-dd'), 'John Doe', 'password1', 'john@example.com', 1);

INSERT INTO "User" (ID, Height, Weight, DateOfBirth, Name, Password, EmailAddress, TrainerID) 
VALUES (2, 165, 60, TO_DATE('1992-02-15', 'yyyy-mm-dd'), 'Jane Smith', 'password2', 'jane@example.com', 2);

INSERT INTO "User" (ID, Height, Weight, DateOfBirth, Name, Password, EmailAddress, TrainerID) 
VALUES (3, 175, 70, TO_DATE('1989-03-10', 'yyyy-mm-dd'), 'Bob Brown', 'password3', 'bob@example.com', 3);

INSERT INTO "User" (ID, Height, Weight, DateOfBirth, Name, Password, EmailAddress, TrainerID) 
VALUES (4, 160, 55, TO_DATE('1993-04-05', 'yyyy-mm-dd'), 'Alice Green', 'password4', 'alice@example.com', 4);

INSERT INTO "User" (ID, Height, Weight, DateOfBirth, Name, Password, EmailAddress, TrainerID) 
VALUES (5, 185, 80, TO_DATE('1988-05-20', 'yyyy-mm-dd'), 'Charlie White', 'password5', 'charlie@example.com', 5);


INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (1, 'Lose 5kg', TO_DATE('2023-12-31', 'yyyy-mm-dd'), 'In Progress', 1);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (2, 'Gain muscle', TO_DATE('2024-06-30', 'yyyy-mm-dd'), 'Not Started', 2);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (3, 'Run marathon', TO_DATE('2023-06-30', 'yyyy-mm-dd'), 'Achieved', 3);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (4, 'Increase stamina', TO_DATE('2024-05-20', 'yyyy-mm-dd'), 'Not Started', 4);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (5, 'Stay fit', TO_DATE('2023-12-15', 'yyyy-mm-dd'), 'Achieved', 5);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (6, 'Complete a 5K run', TO_DATE('2023-10-31', 'yyyy-mm-dd'), 'Achieved', 1);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (7, 'Lose 2kg', TO_DATE('2024-08-30', 'yyyy-mm-dd'), 'Not Started', 2);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (8, 'Walk 10,000 steps daily', TO_DATE('2023-06-30', 'yyyy-mm-dd'), 'Achieved', 3);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (9, 'Swim 20 laps', TO_DATE('2024-12-20', 'yyyy-mm-dd'), 'Not Started', 4);

INSERT INTO Goal (GoalID, Description, TargetDate, Status, UserID) 
VALUES (10, 'Meditate for 30 days', TO_DATE('2023-01-05', 'yyyy-mm-dd'), 'Achieved', 5);


INSERT INTO SleepTracking ("Date", QualityDescription, HoursOfSlept, UserID) VALUES (TO_DATE('2023-10-01', 'yyyy-mm-dd'), 'Good', 7, 1);

INSERT INTO SleepTracking ("Date", QualityDescription, HoursOfSlept, UserID) VALUES (TO_DATE('2023-11-01', 'yyyy-mm-dd'), 'Average', 6, 2);

INSERT INTO SleepTracking ("Date", QualityDescription, HoursOfSlept, UserID) VALUES (TO_DATE('2023-12-01', 'yyyy-mm-dd'), 'Excellent', 8, 3);

INSERT INTO SleepTracking ("Date", QualityDescription, HoursOfSlept, UserID) VALUES (TO_DATE('2023-01-11', 'yyyy-mm-dd'), 'Poor', 5, 4);

INSERT INTO SleepTracking ("Date", QualityDescription, HoursOfSlept, UserID) VALUES (TO_DATE('2023-01-12', 'yyyy-mm-dd'), 'Good', 7, 5);


INSERT INTO MoodTracking (MoodID, MoodDescription, "Date") VALUES (1, 'Feeling great', TO_DATE('2023-10-01', 'yyyy-mm-dd'));

INSERT INTO MoodTracking (MoodID, MoodDescription, "Date") VALUES (2, 'Energetic', TO_DATE('2023-11-01', 'yyyy-mm-dd'));

INSERT INTO MoodTracking (MoodID, MoodDescription, "Date") VALUES (3, 'Excited for the day', TO_DATE('2023-12-01', 'yyyy-mm-dd'));

INSERT INTO MoodTracking (MoodID, MoodDescription, "Date") VALUES (4, 'Tired', TO_DATE('2023-01-11', 'yyyy-mm-dd'));

INSERT INTO MoodTracking (MoodID, MoodDescription, "Date") VALUES (5, 'Refreshed', TO_DATE('2023-01-12', 'yyyy-mm-dd'));


INSERT INTO FitnessPlan (PlanID, StartDate, EndDate, GoalCalories, ActualCalories, UserID) VALUES (1, TO_DATE('2023-01-12', 'yyyy-mm-dd'), TO_DATE('2023-12-31', 'yyyy-mm-dd'), 2000, 1800, 1);

INSERT INTO FitnessPlan (PlanID, StartDate, EndDate, GoalCalories, ActualCalories, UserID) VALUES (6, TO_DATE('2022-09-12', 'yyyy-mm-dd'), TO_DATE('2023-10-01', 'yyyy-mm-dd'), 800, 1240, 1);

INSERT INTO FitnessPlan (PlanID, StartDate, EndDate, GoalCalories, ActualCalories, UserID) VALUES (7, TO_DATE('2021-04-09', 'yyyy-mm-dd'), TO_DATE('2022-11-21', 'yyyy-mm-dd'), 1800, 540, 1);

INSERT INTO FitnessPlan (PlanID, StartDate, EndDate, GoalCalories, ActualCalories, UserID) VALUES (2, TO_DATE('2023-02-12', 'yyyy-mm-dd'), TO_DATE('2023-12-15', 'yyyy-mm-dd'), 2200, 2100, 2);

INSERT INTO FitnessPlan (PlanID, StartDate, EndDate, GoalCalories, ActualCalories, UserID) VALUES (3, TO_DATE('2023-02-12', 'yyyy-mm-dd'), TO_DATE('2023-11-01', 'yyyy-mm-dd'), 2300, 2300, 3);

INSERT INTO FitnessPlan (PlanID, StartDate, EndDate, GoalCalories, ActualCalories, UserID) 
VALUES (4, TO_DATE('2023-03-12', 'yyyy-mm-dd'), TO_DATE('2023-03-17', 'yyyy-mm-dd'), 1900, 2000, 4);

INSERT INTO FitnessPlan (PlanID, StartDate, EndDate, GoalCalories, ActualCalories, UserID) 
VALUES (5, TO_DATE('2023-04-09', 'yyyy-mm-dd'), TO_DATE('2023-04-17', 'yyyy-mm-dd'), 2100, 2100, 5);


INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Running');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (6, 'Running');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (7, 'Running');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (2, 'Cycling');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (3, 'Swimming');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (4, 'Yoga');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (5, 'Weightlifting');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Cycling');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Swimming');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Yoga');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Weightlifting');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Rowing');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Dancing');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Pilates');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Kickboxing');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Skating');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Rock Climbing');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Zumba');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'CrossFit');

INSERT INTO PlanContainsExercise (PlanID, ExerciseName) VALUES (1, 'Tai Chi');


INSERT INTO DietPlanInFitnessPlan (DietID, PlanID) VALUES (1, 1);

INSERT INTO DietPlanInFitnessPlan (DietID, PlanID) VALUES (2, 2);

INSERT INTO DietPlanInFitnessPlan (DietID, PlanID) VALUES (3, 3);

INSERT INTO DietPlanInFitnessPlan (DietID, PlanID) VALUES (4, 4);

INSERT INTO DietPlanInFitnessPlan (DietID, PlanID) VALUES (5, 5);


INSERT INTO Meal (MealID, NutritionalValue, Calories, MealType, MealDescription, DietID) VALUES (1, 50, 500, 'Breakfast', 'Eggs and Toast', 1);

INSERT INTO Meal (MealID, NutritionalValue, Calories, MealType, MealDescription, DietID) VALUES (6, 90, 750, 'Dinner', 'Beef and Soup', 1);

INSERT INTO Meal (MealID, NutritionalValue, Calories, MealType, MealDescription, DietID) VALUES (2, 45, 450, 'Lunch', 'Salad with Tofu', 2);

INSERT INTO Meal (MealID, NutritionalValue, Calories, MealType, MealDescription, DietID) VALUES (3, 60, 600, 'Dinner', 'Grilled Chicken with Vegetables', 3);

INSERT INTO Meal (MealID, NutritionalValue, Calories, MealType, MealDescription, DietID) VALUES (4, 55, 550, 'Breakfast', 'Bacon and Eggs', 4);

INSERT INTO Meal (MealID, NutritionalValue, Calories, MealType, MealDescription, DietID) VALUES (5, 50, 500, 'Lunch', 'Steak with Broccoli', 5);

Commit;
