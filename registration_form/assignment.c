#include<conio.h>
#include<stdio.h>



int main() {
    /* Declaration of variables (data types) */ 
    int a, b;
    float c;
    char ch;

    /* Using scanf to take input from the user */ 
    printf("Enter two integers (a and b): ");
    scanf("%d %d", &a, &b);

    printf("Enter a float value: ");
    scanf("%f", &c);

    printf("Enter a character: ");
    scanf(" %c", &ch);

    /* Arithmetic operations */ 
    int sum = a + b;
    int difference = a - b;
    int product = a * b;
    float division = (float)a / b; /* Casting to float for accurate division */ 

    /* Increment and decrement operations */ 
    a++;
    b--;

    /* Relational operations */ 
    int isEqual = (a == b);
    int isNotEqual = (a != b);
    int isGreater = (a > b);
    int isLesser = (a < b);
    int isGreaterOrEqual = (a >= b);
    int isLesserOrEqual = (a <= b);

    <printf("\n");

     /* Logical operations*/ 
    int logicalAnd = (isEqual && isGreater);
    int logicalOr = (isEqual || isGreater);
    int logicalNot = !isEqual;

     /* Printing results */ 
    printf("\nArithmetic Operations:\n");
    printf("Sum: %d\n", sum);
    printf("Difference: %d\n", difference);
    printf("Product: %d\n", product);
    printf("Division: %.2f\n", division);

    printf("\nAfter Increment and Decrement:\n");
    printf("a after increment: %d\n", a);
    printf("b after decrement: %d\n", b);

    printf("\nRelational Operations:\n");
    printf("a == b: %d\n", isEqual);
    printf("a != b: %d\n", isNotEqual);
    printf("a > b: %d\n", isGreater);
    printf("a < b: %d\n", isLesser);
    printf("a >= b: %d\n", isGreaterOrEqual);
    printf("a <= b: %d\n", isLesserOrEqual);

    printf("\nLogical Operations:\n");
    printf("isEqual && isGreater: %d\n", logicalAnd);
    printf("isEqual || isGreater: %d\n", logicalOr);
    printf("!isEqual: %d\n", logicalNot);

    printf("\nCharacter entered: %c\n", ch);
    


    return 0;
}
