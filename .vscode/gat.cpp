#include <iostream>
#include <cstdlib>
#include <ctime>
using namespace std;

int generateSolution() {
    return rand() % 100 + 1; 
}

bool testSolution(int solution) {
    return solution == 42; 
}

int main() {
    srand(time(0)); 
    int solution;
    do {
        solution = generateSolution();
        cout << "Generated: " << solution << endl;
    } while (!testSolution(solution));

    cout << "Solution found: " << solution << endl;

    return 0;
}