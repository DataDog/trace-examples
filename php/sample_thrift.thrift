include "shared.thrift"

namespace php sample_thrift

service Calculator extends shared.SharedService {

   i32 add(1:i32 num1, 2:i32 num2),
}
