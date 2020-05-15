using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PointOfSale.Data.interfaces
{
	interface IUsers
	{
		IEnumerable<Users> GetUsers();
		void AddUsers(Users users);
		Users GetUsers(int id);
		void UpdateUser(Users users);
		void DeleteUser(int id);
	}
}
